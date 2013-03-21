<?php
// Search Parse www.wobblemedia.com

class Parse
{
	// define variables
	private $_searchBedroom;
	private $_searchTerms;
	private $_searchType;
	private $_searchPropertyType;
	private $_searchAreas;
	private $_minPrice;
	private $_maxPrice;	
	
	// create construct
	public function __construct($txt)
	{
		//clean up query and change all text to lowercase
		$txt = preg_replace("/[^a-zA-Z 0-9]+'/", "", strtolower($txt));
		
		//convert sentence into an array of words.
		$txt = explode(" ", $txt);

		// initiate parse on different search possibilities
		$this->setRooms($txt);
		$this->setTerms($txt);
		$this->setType($txt);
		$this->setPropertyType($txt);
		$this->setPriceRange($txt);
		$this->setAreas($txt);
	}

	
	
	
	
	private function setPriceRange($txt)
	{
		// for each variable in the array
		for ($i=0; $i < count($txt); $i++)
		{
			// set the current word in the array to a possible price
			$price = $txt[$i];
			
			// check if its a number and a possible price
			if (is_numeric($price) && $price > 10)
			{
				// add a possible price range into the price range array
				$priceRange[] = $price;
				
				// order the array from lowest to highest value
				sort($priceRange);
				
				// $this->_price = $priceRange; 
			}	
			
			// if the price range has a single value, set that as the max value for a search
			if (count($priceRange) < 2)
			{
				// set variable
				$this->_minPrice = null;
				$this->_maxPrice = $priceRange[0];
			}	
			// else, if the array has more than one entry...
			else
			{
				// set the min price to the first slot in the array.
				$this->_minPrice = $priceRange[0];
				// set the max price to the last slot in the array.
				$this->_maxPrice = end($priceRange);
			}
		}	
	}	
	
	
	public function getMinPrice()
	{
		return $this->_minPrice;
	}	
	
	public function getMaxPrice()
	{
		return $this->_maxPrice;
	}		

	
	
	
	
	private function setAreas($txt)
	{
		$DaftAPI = new SoapClient("http://api.daft.ie/v2/wsdl.xml", array('features' => SOAP_SINGLE_ELEMENT_ARRAYS));
		$queryString = array('api_key' => "651cc73ee7d51dc8bbd5e0c40536ce66a6fdebe8", 'area_type' => "area");
		$response = $DaftAPI->areas($queryString);

		//create array of areas from area object, create an array of id's in an adjacent array to cross reference them.
		foreach($response->areas as $area) { $areaList[] = strtolower($area->name); $areaID[] = strtolower($area->id); }
		
		// if there are areas found, then go ahead and add them to the search.
		if (count($response->areas) > 1) 
		{	
			//for each entry in $txt, look through the area list to see if it has a match.
			foreach ($txt as $location)
			{	
				//go through the list and see if the query matches the location
				if (array_search($location, $areaList))
				{	
					//go through the array and find its location.
					for($i=0; $i< count($areaList); $i++) 
					{
						//if the location matches the position of the array.
						if($location == $areaList[$i])
						{
							// take that position and use the adjacent areaID array, and itsert its value into the area search array.
							$this->_searchAreas[] = $areaID[$i];
						}
					}
				}
			}
		}	
	}
	
	public function getAreas()
	{
		return $this->_searchAreas;
	}	





	private function setRooms($txt)
	{
		// for each variable in the array
		for ($i=0; $i < count($txt); $i++)
		{
			//reset bedroom variable to the word that is currently being looked at.
			$bedroom = $txt[$i];
			// check if its a number and if its less than 5 - not sure people look for more othan 5 rooms so...
			if (is_numeric($bedroom) && $bedroom < 6)
			{
				//if it is a number less than 5, then set the searchBedroom variable to the current definition of bedroom(which is a number)
				$this->_searchBedroom = $bedroom;
				//escape the loop.
				$i = count($txt);
			}	else
			{
				//if nothing matches, null the search.
				$this->_searchBedroom = NULL;
			}		
		}
	}	
	
	public function getRooms()
	{
		return $this->_searchBedroom;
	}





	private function setTerms($txt)
	{
		//for each entry in $txt
		for ($i=0; $i< count($txt); $i++)
		{	
			//go through the array and look to see if one of keywords is in the $txt array, if it is, set the $terms variable to it.
			if($this->_searchTerms != 'sale' || $this->_searchTerms != 'rental' || $this->_searchTerms != 'rent')
			{
				switch ($txt[$i])
				{
				case 'sale':
					$this->_searchTerms = 'sale';
					$i = count($txt) + 1;
					break;
				case 'rent':
					$this->_searchTerms = 'rent';
					$i = count($txt) + 1;
					break;				
				case 'rental':
					$this->_searchTerms = 'rent';
					$i = count($txt) + 1;
					break;
				case 'let':
					$this->_searchTerms = 'rent';
					$i = count($txt) + 1;
					break;					
				default:
					$this->_searchTerms = 'sale';
				} 			
			}
		}
	}	

	public function getTerms()
	{
		return $this->_searchTerms;
	}	





	private function setType($txt)
	{
		//for each entry in $txt
		for ($i=0; $i< count($txt); $i++)
		{	
			//go through the array and look to see if one of keywords is in the $txt array, if it is, set the $house_type variable to it.
			if($this->_searchType != 'terraced' || $this->_searchType != 'semi-detached' || $this->_searchType != 'detached' || $this->_searchType != 'end-of-terrace' || $this->_searchType != 'townhouse')
			{
				switch ($txt[$i])
				{
				case 'terraced':
				  $this->_searchType = 'terraced';
				  $i = count($txt) + 1;
				  break;
				case 'terrace':
				  $this->_searchType = 'terraced';
				  $i = count($txt) + 1;
				  break;			  
				case 'semi-detached':
				  $this->_searchType = 'semi-detached';
				  $i = count($txt) + 1;
				  break;			  
				case 'detached':
				  $this->_searchType = 'detached';
				  $i = count($txt) + 1;
				  break;
				case 'end-of-terrace':
				  $this->_searchType = 'end-of-terrace';
				  $i = count($txt) + 1;
				  break;
				case 'townhouse':
				  $this->_searchType = 'townhouse';
				  $i = count($txt) + 1;
				  break;			  
				default:
				  $this->_searchType = null;
				} 			
			}
		}	
	}	

	
	public function getType()
	{
		return $this->_searchType;
	}	

	
	
	
	
	private function setPropertyType($txt)
	{
		for ($i=0; $i< count($txt); $i++)
		{	
			//go through the array and look to see if one of keywords is in the $txt array, if it is, set the $property_type variable to it.
			if($this->_searchPropertyType != 'apartment' || $this->_searchPropertyType != 'house' || $this->_searchPropertyType != 'site')
			{
				switch ($txt[$i])
				{
				case 'apartment':
				  $this->_searchPropertyType = 'apartment';
				  $i = count($txt) + 1;
				  break;
				case 'house':
				  $this->_searchPropertyType = 'house';
				  $i = count($txt) + 1;
				  break;
				case 'duplex':
				  $this->_searchPropertyType = 'duplex';
				  $i = count($txt) + 1;
				  break;			  
				case 'site':
				  $this->_searchPropertyType = 'site';
				  $i = count($txt) + 1;
				  break;			  
				default:
				  $this->_searchPropertyType = null;
				} 			
			}
		}		
	}
	
	public function getPropertyType()
	{
		return $this->_searchPropertyType;
	}	

}
?>
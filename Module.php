<?PHP
	Namespace Season ;

	Abstract Class Module
	{
		Private $SeasonHandle = Null ;
		Private $Identification = '' ;
		Protected $Dependencies = Array ( ) ;
	
		Public Function __Construct ( & $SeasonHandle , $Identification )
		{
			$this->SeasonHandle = $SeasonHandle ;
			$this->Identification = $Identification ;
		}
		
		Protected Function CheckParameter ( $Parameter , $Size = Null )
		{
			If ( ! $Parameter === Null || Empty ( $Parameter ) || ( $Size && SizeOf ( $Parameter ) < $Size ) )
				Return False ;
				
			Return True ;
		}
		
		Public Function & GetSeasonHandle ( )
		{
			Return $this->SeasonHandle ;
		}
		
		Public Function GetIdentification ( )
		{
			Return $this->Identification ;
		}
		
		Public Function GetDependencies ( )
		{
			Return $this->Dependencies ;
		}
	
		Public Function __Call ( $Name , $Parameter )
		{
			Return Null ;
		}
		
		Public Function __Set ( $Name , $Value )
		{
		}
		
		Public Function __Get ( $Name )
		{
		}
	}
?>
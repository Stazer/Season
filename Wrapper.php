<?PHP
	Namespace Season ;
	
	Class Wrapper
	{
		Private $Instance ;
		Private $Modules ;
		
		Public Function __Construct ( & $Season , & $Modules , $Class , $Identification , $Parameter )
		{
			$this->Instance = New $Class ( $Season , $Identification , $Parameter ) ;
			$this->Modules = & $Modules ;
		}
		
		Public Function __Call ( $Name , $Parameter )
		{
			If ( ! $this->Instance->Season || ! $this->Instance->Season->GetEventHandling ( ) )
				Return ;
		
			$Before = 'Before'.$this->Instance->Identification.$Name ;
					
			Foreach ( $this->Modules As & $Module )
			{
				$Module->Instance->$Before ( $Parameter ) ;
			}
			
			$Return = $this->Instance->$Name ( $Parameter ) ;
			
			$After = 'After'.$this->Instance->Identification.$Name ;
			
			Foreach ( $this->Modules As & $Module )
			{
				$Module->Instance->$After ( $Parameter ) ;
			}			
			
			Return $Return ;
		}
		
		Public Function & GetInstance ( )
		{
			Return $this->Instance ;
		}
	}
?>
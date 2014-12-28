<?PHP
	Namespace Season ;

	Class Handle
	{
		Private $Modules = Array ( ) ;
		Private $Dependencies = Array ( ) ;
		
		Private $EventHandling = False ;
				
		Public Function __Destruct ( )
		{
			$this->Modules = Array_Reverse ( $this->Modules ) ;
		
			$this->Trigger ( 'Finalize' ) ;
		}
		
		Public Function SetEventHandling ( $EventHandling )
		{
			$this->EventHandling = $EventHandling ;
		}
		Public Function GetEventHandling ( )
		{
			Return $this->EventHandling ;
		}

		Public Function Trigger ( $Method , $Parameter = Null )
		{
			Foreach ( $this->Modules As & $Module )
			{
				$Module->$Method ( $Parameter ) ;
			}
		}
		
		Public Function GetModuleIdentifications ( )
		{
			$ModuleIdentifications = Array ( ) ;
			
			Foreach ( $this->Modules As $Key => $Module )
			{
				Array_Push ( $ModuleIdentifications , $Key ) ;
			}
			
			Return $ModuleIdentifications ;
		}
		
		Public Function Main ( )
		{
			$this->Trigger ( 'Main' ) ;
		}
		
		Public Function LoadFile ( $Directory , $File )
		{
			If ( $Directory == '' )
				$Directory = '.' ;
		
			If ( ! File_Exists ( $Directory.'/'.$File ) )
				Return False ;
				
			Require_Once ( $Directory.'/'.$File ) ;
			
			Return True ;
		}
		
		Public Function AddModule ( $Namespace , $Class , $Parameter = Null )
		{
			If ( $Namespace != '' )
				$Namespace = '\\'.$Namespace ;
				
			$Identification = Str_Replace ( '\\' , '' , $Namespace ).$Class ;
				
			If ( IsSet ( $this->Modules [ $Identification ] ) || IsSet ( $this->Dependencies [ $Identification ] ) )
				Return False ;
		
			$Module = New \Season\Wrapper ( $this , $this->Modules , $Namespace.'\\'.$Class , $Identification , $Parameter ) ;

			$Instance = $Module->GetInstance ( ) ;
			
			//If ( Property_Exists ( $Namespace.'\\'.$Class , 'Dependencies' ) )
			{
				$NeedDependency = False ;

				Foreach ( $Instance->GetDependencies ( ) As $Dependency )
				{
					If ( ! IsSet ( $this->Modules [ $Dependency ] ) )
					{
						If ( ! $NeedDependency )
							$NeedDependency = Array ( ) ;
							
						Array_Push ( $NeedDependency , $Dependency ) ;
					}
				}

				If ( $NeedDependency !== False )
				{
					$this->Dependencies [ $Identification ] = Array ( Array ( $Namespace , $Class ) , $NeedDependency ) ;
			
					Return False ;
				}
			}

			$Return = $Module->GetInstance ( )->Initialize ( ) ;
			
			If ( $Return === False )
				Return False ;
				
			$this->Modules [ $Identification ] = $Module ;

			Foreach ( $this->Dependencies As $KeyA => & $Dependency )
			{
				Foreach ( $Dependency [ 1 ] As $KeyB => & $Entry )
				{
					If ( $Identification == $Entry )
						UnSet ( $Dependency [ 1 ] [ $KeyB ] ) ;
				}

				If ( Empty ( $Dependency [ 1 ] ) )
				{
					$NewClass = $Dependency [ 0 ] [ 0 ] ;
					$NewNamespace = $Dependency [ 0 ] [ 1 ] ;

					UnSet ( $this->Dependencies [ $KeyA ] ) ;

					$this->AddModule ( $NewClass , $NewNamespace , $KeyA ) ;
				}
			}
			
			Return True ;
		}
		
		Public Function & __Call ( $Name , $Parameter )
		{
			If ( StrPos ( $Name , 'Get' ) === 0 )
			{
				$Name = SubStr ( $Name , 3 ) ;
				
				Return $this->GetModule ( $Name ) ;
			}
			
			$Null = Null ;
			
			Return $Null ;
		}
		
		Public Function & GetModule ( $Identification )
		{
			If ( IsSet ( $this->Modules [ $Identification ] ) )
				Return $this->Modules [ $Identification ]->GetInstance ( ) ;
				
			$Null = Null ;
			
			Return $Null ;
		}

		Public Function RemoveModule ( $Identification )
		{
			Foreach ( $this->Modules As & $Module )
			{
				If ( $Module->GetIdentification ( ) == $Identification )
				{
					$Module->Finalize ( ) ;
					
					UnSet ( $Module ) ;
					
					Break ;
				}
			}
		}
	}
?>
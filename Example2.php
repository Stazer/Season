<?PHP
	Namespace Season ;
	
	Class Example2 Extends \Season\Module
	{
		Protected $Dependencies = Array ( 'SeasonExample1' ) ;

		Public Function Initialize ( )
		{
			Echo 'Example2 loaded!' ;
		}
	}
?>
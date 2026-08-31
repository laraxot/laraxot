# Phpstan result

   0/457 [░░░░░░░░░░░░░░░░░░░░░░░░░░░░]   0%[1G[2K  20/457 [▓░░░░░░░░░░░░░░░░░░░░░░░░░░░]   4%[1G[2K 180/457 [▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░]  39%[1G[2K 200/457 [▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░]  43%[1G[2K 220/457 [▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░]  48%[1G[2K 240/457 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░]  52%[1G[2K 280/457 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░]  61%[1G[2K 320/457 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░]  70%[1G[2K 360/457 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░]  78%[1G[2K 380/457 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░]  83%[1G[2K 417/457 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░]  91%[1G[2K 457/457 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

 ------ ------------------------------------------------------- 
  Line   Modules/Geo/app/Forms/Components/CoordinatePicker.php  
 ------ ------------------------------------------------------- 
  129    Syntax error, unexpected EOF on line 129               
 ------ ------------------------------------------------------- 

 -- ----------------------------------------------------------------------------------------------------------- 
     Error                                                                                                      
 -- ----------------------------------------------------------------------------------------------------------- 
     Internal error: Class                                                                                      
     Modules\Geo\Filament\Forms\Components\CoordinatePicker was not found                                       
     while trying to analyse it - discovering symbols is probably not                                           
     configured properly. while analysing file                                                                  
     /var/www/_bases/base_fixcity_fila5/laravel/Modules/Geo/app/Filament/Forms/Components/CoordinatePicker.php  
                                                                                                                
     Run PHPStan with -v option and post the stack trace to:                                                    
     https://github.com/phpstan/phpstan/issues/new?template=Bug_report.yaml                                     
                                                                                                                
 -- ----------------------------------------------------------------------------------------------------------- 

 [ERROR] Found 2 errors                                                         

⚠️  Result is incomplete because of severe errors. ⚠️
   Fix these errors first and then re-run PHPStan
   to get all reported errors.

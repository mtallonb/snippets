package html.gestor;

import java.io.IOException;
import aviones.Util;
import java.util.Properties;
import html.ParseHtml;
import aviones.Ente;
import aviones.ListadoEntes;

public class GestorHtml {
  // Identificaci�n de la plataforma Windows
  public static final String WIN_ID = "Windows";

  // El navegador por defecto utilizado en Windows
  public static final String WIN_PATH = "rundll32";

  // El Flag que hay que poner para presentar una direcci�n URL
  public static final String WIN_FLAG = "url.dll,FileProtocolHandler";

  // El navegador por defecto en Unix
  private static final String UNIX_PATH = "netscape";

  // Las opciones de la l�nea de comandos para Netscape
  private static final String UNIX_FLAG = "-remote openURL";

  public String RutaHtml;
  public String RutaAyuda;

  public String RutaTemp;

  public GestorHtml() {
    Properties properties = new Properties();

    try {
      properties.load(getClass().getResourceAsStream(Util.getClassName(this.
          getClass()) + ".properties"));
    }
    catch (IOException ex) {
      System.out.println("No existe el properties");
    }
    this.RutaHtml = properties.getProperty("RUTA_HTML");
    this.RutaAyuda = properties.getProperty("RUTA_AYUDA");
    /*this.RutaTemp=System.getProperty("user.home");
      System.out.println(RutaTemp);*/
  }

  /**
   * Presenta un fichero en el navegador del sistema. Si se quiere presentar
   * un fichero, hay que introducir el camino completo
   */

  public static void presentaURL( String url) {
     boolean windows = sobrePlataformaWindows();
     String cmd = null;

     try {
       if( windows ) {
         // cmd = 'rundll32 url.dll,FileProtocolHandler http://...'
         cmd = WIN_PATH +" "+ WIN_FLAG +" "+ url;
         Process p = Runtime.getRuntime().exec(cmd);
         }
       else {
         // En Unix, Netscape tiene que ejecutarse con el par�metro
         // "-remote", por si est� ya lanzada otra copia; lo que se
         // hace es observar el valor de retorno, si devuelve 0, se
         // habr� lanzado correctamente y en cualquier otro caso,
         // ser� que no est� corriendo el navegador, as� que hay que
         // lanzarlo

         // cmd = 'netscape -remote openURL(http://java.sun.com)'
         cmd = UNIX_PATH +" "+ UNIX_FLAG +"("+ url +")";
         Process p = Runtime.getRuntime().exec( cmd );

         try {
           // Esperamos al c�digo de salida, si es 0 indica que el
           // comando ha funcionado; y en otro caso, es necesario
           // arrancar directamente el navegador
           int exitCode = p.waitFor();

           // El comando ha fallado, as� que hay que lanzar el
           // navegador
           if( exitCode != 0 ) {
             // cmd = 'netscape http://java.sun.com'
             cmd = UNIX_PATH +" "+ url;
             p = Runtime.getRuntime().exec(cmd);
             }
         } catch( InterruptedException e ) {
           System.err.println(
             "Error lanzando el navegador, comando='"+ cmd +"'" );
           e.printStackTrace();
           }
         }
     } catch( IOException e ) {
       System.err.println(
         "No se puede invocar al navegador, comando="+ cmd );
           e.printStackTrace();
       }
   }


   /**
    * Este m�todo intenda saber si la aplicaci�n est� corriendo bajo
    * Windows, o cualquier otra plataforma, comprobando el valor de
    * la propiedad "os.name"
    */

   public static boolean sobrePlataformaWindows() {
     String os = System.getProperty( "os.name" );

     if( os != null && os.startsWith( WIN_ID ) )
       return( true );
     else
       return( false );
     }


     public void crearHtml(ListadoEntes e){
       ParseHtml parse=new ParseHtml(this.RutaHtml,e);

     }




}



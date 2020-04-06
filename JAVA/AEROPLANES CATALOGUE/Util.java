package aviones;

import java.io.*;
import javax.swing.JFileChooser;
import javax.swing.JFrame;
import aviones.utiles.FiltroXML;
import aviones.utiles.FiltroImagen;
import aviones.utiles.FiltroAudioVideo;
import javax.swing.JOptionPane;
import aviones.utiles.FiltroPDF;

/**
 * Utilidades de manejo de datos en general
 * @version %I%, %G%
 * @since 1.0
 */
public class Util {

  final public static byte FCImagen = 1;
  final public static byte FCXml = 2;
  final public static byte FCAudioVideo = 3;
  final public static byte FCGuardarXML = 4;
  final public static byte FCGuardarPDF = 5;
  final public static byte DialogoInfo = 6;

  public static String Pdf = "pdf";
  public static String Xml = "xml";
  public static String Jpg = "jpg";
  public static String Jpeg = "jpeg";
  public static String Gif = "gif";
  public static String Bmp = "bmp";
  public static String Au = "au";
  public static String Avi = "avi";
  public static String Midi = "midi";
  public static String Mpeg = "mpeg";
  public static String Wav = "wav";
  public static String Mp3 = "mp3";

  /**
   * Convierte una valor a cadena comprobando su valor null
   * @param Objeto
   * @return Cadena convertida
   */
  public static String ToString(Object Objeto) {
    if (Objeto == null) {
      return null;
    }

    if (Objeto.toString().trim().length() == 0) {
      return null;
    }

    return Objeto.toString();
  }

  /**
   * Convierte una valor a decimal comprobando su valor null
   * @param Objeto
   * @return Decimal convertido
   */
  public static float Tofloat(Object Objeto) {
    if (Objeto == null) {
      return 0;
    }

    if (Objeto.toString().trim().length() == 0) {
      return 0;
    }

    return Float.parseFloat(Objeto.toString());
  }

  /**
   * Convierte un valor a entero comprobando su valor null
   * @param Objeto
   * @return Entero convertido (0 un entero no valido)
   */
  public static int Toint(Object Objeto) {
    if (Objeto == null) {
      return 0;
    }

    if (Objeto.toString().trim().length() == 0) {
      return 0;
    }

    return Integer.parseInt(Objeto.toString());
  }

  /**
   * Convierte un valor a booleano comprobando su valor null
   * @param Objeto
   * @return Booleano convertida
   */
  public static boolean Toboolean(Object Objeto) {
    if (Objeto == null) {
      return false;
    }
    if (Objeto.toString().trim().toLowerCase().compareToIgnoreCase("on") >= 0) {
      return true;
    }
    if (Objeto.toString().trim().toLowerCase().compareToIgnoreCase("off") >= 0) {
      return false;
    }

    if (Objeto.toString().trim().toLowerCase().compareToIgnoreCase("true") >= 0) {
      return true;
    }
    if (Objeto.toString().trim().toLowerCase().compareToIgnoreCase("false") >=
        0) {
      return false;
    }

    if (Objeto.toString().trim().toLowerCase().compareTo("1") >= 0) {
      return true;
    }
    if (Objeto.toString().trim().toLowerCase().compareTo("0") >= 0) {
      return false;
    }

    if (Objeto.toString().trim().length() == 0) {
      return false;
    }

    return Boolean.valueOf( (String) Objeto).booleanValue();
  }

  /**
   * Convierte una valor a short comprobando su valor null
   * @param Objeto
   * @return Numero convertido
   */
  public static short Toshort(Object Objeto) {
    if (Objeto == null) {
      return 0;
    }

    if (Objeto.toString().trim().length() == 0) {
      return 0;
    }

    return Short.parseShort(Objeto.toString());
  }

  /**
   * Reemplaza un texto por otro en una cadena. Reemplaza todas las ocurrencias.
   * @param origen Cadena donde se realiza el reemplazo
   * @param reemplazar Cadena a reemplazar
   * @param reemplazo Nueva cadena que sustituye a la anterior
   * @return Cadena resultado tras el reemplazo
   */
  public static String Replace(String origen, String reemplazar,
                               String reemplazo) {
    int index = -1;
    String resultado = origen;

    while ( (index = resultado.indexOf(reemplazar, index)) > -1) {
      resultado = resultado.substring(0, index) + reemplazo +
          resultado.substring(index + reemplazar.length(), resultado.length());
      index = index + reemplazo.length();
    }

    return resultado;
  }

  /**
   * Devuelve el nombre de una determinada clase, eliminando el paquete donde se encuentra
   * @param Clase Estructura de la clase en cuestion <Object>
   * @return Nombre de la clase
   */
  public static String getClassName(Class Clase) {
    String Nombre = Clase.getName();
    int index = -1;

    if ( (index = Nombre.lastIndexOf(".")) > -1) {
      return Nombre.substring(index + 1, Nombre.length());
    }
    else {
      return Nombre;
    }
  }

  /**
   * Provoca un retardo en la ejecución actual, en milisegundos
   * @param Milisegundos
   */
  public static void Retardo(int Milisegundos) {
    try {
      Thread.sleep(Milisegundos);
    }
    catch (Exception e) {
      e.printStackTrace();
    }
  }

  /**
   * Normaliza una cadena de texto de javascript
   * @param cadena Cadena a normalizar
   * @return Cadena normalizada
   */

  public static String NormalizarJavascript(String cadena) {
    String tmp;

    tmp = Replace(cadena, "'", "\'");
    tmp = Replace(tmp, "\"", "\\\"");
    tmp = Replace(tmp, "\n", " ");
    tmp = Replace(tmp, "\t", " ");

    return tmp;
  }

  /**
   * Crea un FileChooser
   * @param Padre Ventana donde mostrarlo
   * @param tipo de FileChooser que se desea crear
   * @return Ruta de fichero seleccionado
   */

  public static String CrearFileChooser(JFrame Padre, byte Tipo) {
    JFileChooser fc = new JFileChooser();
    switch (Tipo) {
      case (FCXml):
        FiltroXML filtro = new FiltroXML();
        fc.setFileFilter(filtro);
        fc.setCurrentDirectory(new File("./datos"));
        fc.setFileSelectionMode(JFileChooser.FILES_ONLY);

        if (fc.showOpenDialog(Padre) == JFileChooser.APPROVE_OPTION) {
          File archivo = fc.getSelectedFile();
          return archivo.getAbsolutePath();
        }

        break;

      case (FCImagen):
        FiltroImagen filtroI = new FiltroImagen();
        fc.setFileFilter(filtroI);
        fc.setCurrentDirectory(new File("./img/aviones"));
        fc.setFileSelectionMode(JFileChooser.FILES_ONLY);

        if (fc.showOpenDialog(Padre) == JFileChooser.APPROVE_OPTION) {
          File archivo = fc.getSelectedFile();
          return archivo.getAbsolutePath();
        }
        break;

      case (FCAudioVideo):
        FiltroAudioVideo filtroAV = new FiltroAudioVideo();
        fc.setFileFilter(filtroAV);
        fc.setCurrentDirectory(new File("./clips"));
        fc.setFileSelectionMode(JFileChooser.FILES_ONLY);

        if (fc.showOpenDialog(Padre) == JFileChooser.APPROVE_OPTION) {
          File archivo = fc.getSelectedFile();
          return archivo.getAbsolutePath();
        }

        break;

      case (FCGuardarXML):
        FiltroXML filtroXML = new FiltroXML();
        fc.setFileFilter(filtroXML);
        fc.setCurrentDirectory(new File("./datos"));
        fc.setFileSelectionMode(JFileChooser.FILES_ONLY);

        if (fc.showSaveDialog(Padre) == JFileChooser.APPROVE_OPTION) {
          File archivo = fc.getSelectedFile();
          String nombre=archivo.getAbsolutePath();
          if(getExtension(nombre) == null)
           return (nombre + ".xml");
          return nombre;
        }

        break;

      case (FCGuardarPDF):
      FiltroPDF filtroPDF = new FiltroPDF();
      fc.setFileFilter(filtroPDF);
      fc.setCurrentDirectory(new File("./datos"));
      fc.setFileSelectionMode(JFileChooser.FILES_ONLY);

      if (fc.showSaveDialog(Padre) == JFileChooser.APPROVE_OPTION) {
        File archivo = fc.getSelectedFile();
        String nombre=archivo.getAbsolutePath();
        if(getExtension(nombre) == null)
         return (nombre + ".pdf");
        return nombre;
      }

      break;

    }

    return null;
  }

  /*
   * Obtiene la extensión de un fichero
   */
  public static String getExtension(File f) {
    String ext = null;
    String s = f.getName();
    int i = s.lastIndexOf('.');

    if (i > 0 && i < s.length() - 1) {
      ext = s.substring(i + 1).toLowerCase();
    }
    return ext;
  }

  public static String getExtension(String s){

    String ext = null;
    int i = s.lastIndexOf('.');

    if (i > 0 && i < s.length() - 1) {
      ext = s.substring(i + 1).toLowerCase();
    }
    return ext;
  }

  public static String getNombre(String nombre) {
    if (nombre != null) {
      int i = nombre.lastIndexOf('\\');

      if (i > 0 && i < nombre.length() - 1) {
        nombre = nombre.substring(i + 1).toLowerCase();
      }
    }
    return nombre;
  }

  public static void crearDialogo(byte tipo) {
    switch (tipo) {
      case (DialogoInfo):
        JOptionPane.showMessageDialog(null,
                                      "No ha seleccionado ningún avión para exportar",
                                      "Aviso",
                                      JOptionPane.INFORMATION_MESSAGE);
        break;
    }

  }

}

package xml.gestor;

import org.w3c.dom.*;
import javax.xml.parsers.*;
import org.apache.xml.utils.DefaultErrorHandler;
import java.util.Properties;
import java.io.IOException;
import aviones.Util;
import aviones.Avion;
import aviones.ListadoEntes;
import java.util.Iterator;
import aviones.Ente;


public class GestorXml {
  public static String RutaXml;
  public static String RutaSalidaXml;

  public GestorXml() {

    Properties properties = new Properties();

    try {
      properties.load(getClass().getResourceAsStream(Util.getClassName(this.
          getClass()) + ".properties"));
    }
    catch (IOException ex) {
      System.out.println("No existe el properties");
    }
    this.RutaXml = properties.getProperty("RUTA_XML");
    this.RutaSalidaXml=properties.getProperty("RUTA_SALIDA");
  }

  public void leerXml(ListadoEntes l) {
    Document document = null;
    Avion a;
    try {

      DocumentBuilderFactory documentBuilderFactory =
          DocumentBuilderFactory.newInstance();
      /* Configuramos el factory:
       *
       * 1. Activar Validación.
       * 2. Hacemos secciones CDATA transparentes a nuestro  código.
       * 3. Ignoramos Comentarios
       * 4. Ignoramos espacios en blanco.
       *
       * NOTA: referencia a entidades son ignoradas por defecto.
       */
      documentBuilderFactory.setValidating(true);
      documentBuilderFactory.setCoalescing(true);
      documentBuilderFactory.setIgnoringComments(true);
      documentBuilderFactory.setIgnoringElementContentWhitespace(true);

      // Creamos una instancia del DOM parser.
      DocumentBuilder documentBuilder =
          documentBuilderFactory.newDocumentBuilder();
      // Establecer un manejador de Error.
      documentBuilder.setErrorHandler(new DefaultErrorHandler());
      //Obtenemos el Objeto Document
      document = documentBuilder.parse(RutaXml);

      NodeList nodos_i = document.getDocumentElement().getChildNodes();
      System.out.println("num nodos: " + nodos_i.getLength());
      for (int i = 0; i < nodos_i.getLength(); i++) {

        Node nodo_i = nodos_i.item(i);

        if (nodo_i.getNodeType() == Node.ELEMENT_NODE
            && ( (Element) nodo_i).getTagName().equals("avion")) {
          a = new Avion();
          a.Clase=a.Avion;
          Element avion = (Element) nodo_i;
          a.setTipo(avion.getAttribute("tipo"));
          a.setPais(avion.getAttribute("pais"));

          NodeList nodos_j = avion.getChildNodes();
          for (int j = 0; j < nodos_j.getLength(); j++) {
            Node nodo_j = nodos_j.item(j);
            if (nodo_j.getNodeType() == Node.ELEMENT_NODE) {
              String nombreNodo = nodo_j.getNodeName();
              String valorNodo = nodo_j.getChildNodes().item(0).getNodeValue();
              if (nombreNodo.equals("marca")) a.Marca = valorNodo;
              else
              if (nodo_j.getNodeName().equals("modelo")) a.Modelo = valorNodo;
              else
              if (nodo_j.getNodeName().equals("nombrecomun")) a.NombreComun =
                  valorNodo;
              else
              if (nodo_j.getNodeName().equals("pasajeros")) a.Pasajeros =
                  Integer.parseInt(valorNodo);
              else
              if (nodo_j.getNodeName().equals("envergadura")) a.Envergadura =
                  Double.parseDouble(valorNodo);
              else
              if (nodo_j.getNodeName().equals("velocidad")) a.Velocidad =
                  Double.parseDouble(valorNodo);
              else
              if (nodo_j.getNodeName().equals("fuerzaempuje")) a.FuerzaEmpuje =
                  Double.parseDouble(valorNodo);
              else
              if (nodo_j.getNodeName().equals("propulsion")){ a.NumMotores =
                    Byte.parseByte(valorNodo);
                Element tipPropulsion = (Element) nodo_j;
                a.setPropulsion(tipPropulsion.getAttribute("tipo"));
              }
              else
              if (nodo_j.getNodeName().equals("imagen")) a.Imagen = valorNodo;
            }
          }
           //a.muestraAvion();
           l.VectorAviones.add(a);
        }
      }

     }
     catch (Exception ex) {
        System.out.println("Se produjo excepcion: El fichero XML parece no ser válido "+ex.toString());
      }
     }

     public void guardarXml(ListadoEntes l){
       try {
         java.io.FileWriter fw = new java.io.FileWriter(this.RutaSalidaXml);
         java.io.BufferedWriter bw = new java.io.BufferedWriter(fw);
         bw.write("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"+
                  "<!DOCTYPE raiz SYSTEM \"aviones.dtd\">\n<raiz>\n");
         Iterator lIt = l.VectorAviones.iterator();
         //System.out.println(l.VectorAviones.size());
         while (lIt.hasNext()) {
           Object Obj=lIt.next();
           if(Obj.getClass().toString().equals("class aviones.Avion") && ((Ente) Obj).Seleccionado){
             System.out.println("Es avion");
             bw.write(((Avion) Obj).getDatosXml());
           }
            else
              System.out.println("Es helicóptero o no esta seleccionado");
         }

         bw.write("</raiz>");
         bw.close();
         fw.close();
       }catch (java.io.IOException ex) {
            System.out.println("Excepcion de E/S: "+ex.getMessage());
        }
     }

}

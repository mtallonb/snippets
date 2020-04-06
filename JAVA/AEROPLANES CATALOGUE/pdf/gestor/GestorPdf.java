package pdf.gestor;

import javax.xml.transform.*;
import javax.xml.transform.stream.*;
import javax.xml.transform.sax.*;
import org.apache.fop.apps.*;
import java.io.*;
import java.util.Properties;
import aviones.Util;
import javax.xml.transform.stream.StreamSource;
import html.gestor.GestorHtml;

public class GestorPdf {

  public String RutaXml;
  private String RutaXsl;
  public String RutaPdf;

  public GestorPdf() {

    Properties properties = new Properties();

      try {
        properties.load(getClass().getResourceAsStream(Util.getClassName(this.
            getClass()) + ".properties"));
      }
      catch (IOException ex) {
        System.out.println("No existe properties");
      }
      this.RutaXml = properties.getProperty("RUTA_XML");
      this.RutaXsl = properties.getProperty("RUTA_XSL");
      this.RutaPdf = properties.getProperty("RUTA_PDF");
    }


  /**
   * Presenta un fichero en el navegador del sistema. Si se quiere presentar
   * un fichero, hay que introducir el camino completo
   */
  public void presentaPDF() {
    this.GenerarFicheroPdf();
    GestorHtml.presentaURL(RutaPdf);

  }

  /**
   * Genera un pdf a partir de una plantilla (xsl) y unos datos (xm).
   * @param xml Documento xml con los datos.
   * @param rutaXsl Ruta del archivo xsl que se utiliza como plantilla.
   * @param out Ruta del archivo pdf que se ha de crear.
   * @return boolean
   */
  public void GenerarFicheroPdf() {
    try {

      Driver driver = new Driver();

      // Setup Renderer (output format)
      driver.setRenderer(Driver.RENDER_PDF);

      // Setup output
      OutputStream out = new java.io.FileOutputStream(this.RutaPdf);
      out = new java.io.BufferedOutputStream(out);

      try {
        driver.setOutputStream(out);

        // Setup XSLT
        TransformerFactory factory = TransformerFactory.newInstance();
        Transformer transformer = factory.newTransformer(new StreamSource(this.
            RutaXsl));

        // Set the value of a <param> in the stylesheet
        transformer.setParameter("versionParam", "2.0");

        // Setup input for XSLT transformation
        Source src = new StreamSource(this.RutaXml);

        // Resulting SAX events (the generated FO) must be piped through to FOP
        Result res = new SAXResult(driver.getContentHandler());

        // Start XSLT transformation and FOP processing
        transformer.transform(src, res);
      }
      finally {
        out.close();
      }

      System.out.println("Éxito al exportar");
    }
    catch (Exception e) {
      e.printStackTrace(System.err);
      System.exit( -1);
    }
  }

  /*
    //Devuelve un array de bytes pdf para pintarlos en una pantalla a partir del fichero de datos xml y la ruta del xsl
    public byte[] GenerarPdf(String xml, String rutaXsl)
    {

      try
      {
        ByteArrayOutputStream pdf = new ByteArrayOutputStream();
        Driver driver = new Driver();
        driver.setRenderer(Driver.RENDER_PDF);

        StreamSource sxml = new StreamSource(new ByteArrayInputStream(xml.getBytes("UTF-8")));
        StreamSource xsl = new StreamSource(rutaXsl);

        driver.setOutputStream(pdf);

        TransformerFactory factory = TransformerFactory.newInstance();
        Transformer transformer = factory.newTransformer(xsl);

        transformer.setParameter("versionParam", "2.0");

        Result res = new SAXResult(driver.getContentHandler());

        transformer.transform(sxml, res);

        byte[] salida = pdf.toByteArray();
        pdf.close();

        return salida;
      }
      catch (Exception e)
      {
     e.printStackTrace();
        return null;
      }
    }*/
}

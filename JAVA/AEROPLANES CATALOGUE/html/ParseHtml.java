package html;

import java.util.Date;
import java.text.SimpleDateFormat;
import aviones.Ente;
import aviones.Avion;
import java.util.Iterator;
import aviones.ListadoEntes;
import aviones.ventanas.gestor.GestorVentanas;

public class ParseHtml {

  private Date Fecha;

  private String Ruta;

  private ListadoEntes Entes;

  /** Constructor del Parse */
  public ParseHtml(String ruta, ListadoEntes l) {
    Ruta = ruta;
    Entes = l;
    parse();

  }

  void parse() {
    Avion a;

    try {
      java.io.FileWriter fw = new java.io.FileWriter(Ruta);
      java.io.BufferedWriter bw = new java.io.BufferedWriter(fw);

      bw.write("<html>\n");
      bw.write("<head>\n");
      bw.write("<title>Aviones Seleccionados</title>\n");
      bw.write(
          "<link rel=\"stylesheet\" type=\"text/css\" href=\"aviones.css\">");
      bw.write("</head>\n");
      bw.write("<body>\n");
      bw.write("<br>\n");
      bw.write(
          "<left><img class=\"icono\"  src=\"../"+GestorVentanas.RutaIco+"\"><br>\n");
      bw.write("<hr></left>\n");

      bw.write("<div align=\"center\"><br>"
               + "<a name=\"INICIO\"/><br>"
               + Entes.DameMarcadoresParse()
               + "</div>");

      bw.write("<CENTER>"
               + "<APPLET CODE=\"org.helio.soja.SojaApplet.class\""
               + " ARCHIVE=\"soja.jar\" CODEBASE=\"./smil\""
               + " WIDTH=\"400\" HEIGHT=\"300\">"
               + "<PARAM NAME=\"source\" VALUE=\"./smil/inicio.smil\">"
               + "</APPLET>"
               + "</CENTER>");

      Iterator lIt = Entes.VectorAviones.iterator();
      System.out.println(Entes.VectorAviones.size());
      while (lIt.hasNext()) {
        Ente Dato = (Ente) lIt.next();
        if (Dato.Clase == Dato.Avion && Dato.Seleccionado) {
          a = new Avion();
          a = (Avion) Dato;

          bw.write("<hr><a name=\"" + a.Modelo + "\"></a><br> ");
          bw.write("<table width=\"100%\">\n");
          bw.write("<tr>\n");
          bw.write("<td width=\"50%\"><h1>Avión elegido</h1></td>\n");
          bw.write(
              "<td width=\"50%\"><h1>Caracter&iacute;sticas T&eacute;cnicas</h1></td>\n");
          bw.write("</tr>\n");
          bw.write("<tr>\n");
          bw.write("<td width=\"50%\">");
          bw.write("<table width=\"100%\">");
          bw.write("<tr><td width=\"100%\"><center><img class=\"avion\" src=\"../" +
                   GestorVentanas.RutaImagenes +
                   Dato.Imagen +
                   "\" alt=\"\" \"><center></td>\n<td width=\"0%\"></td\n</tr>");
          bw.write(
              "<tr><td width = \"100%\"><b><h2 align=\"center\">" +
              Dato.Marca + "&nbsp;" + Dato.Modelo + "&nbsp;" + "(" +
              Dato.NombreComun + ")" + "  </h2></td></tr>");
          bw.write("</table>\n");
          bw.write("</td>\n");
          bw.write("<td width=\"50%\">\n");
          bw.write("<table width=\"100%\" border=\"1\">\n");
          bw.write("<tr>\n");
          bw.write("<td width = \"50%\"><b>Pasajeros</td>\n");
          bw.write("<td width = \"50%\">" + a.Pasajeros + "</td>");
          bw.write("</tr>\n");
          bw.write("<tr>\n");
          bw.write("<td width = \"50%\"><b>Envergadura (m)</td>\n");
          bw.write("<td width = \"50%\">" + a.Envergadura + "</td>");
          bw.write("</tr>\n");
          bw.write("<tr>\n");
          bw.write("<td width = \"50%\"><b>Velocidad (km/h)</td>\n");
          bw.write("<td width = \"50%\">" + a.Velocidad + "</td>");
          bw.write("</tr>\n");
          bw.write("<tr>\n");
          bw.write("<td width = \"50%\"><b>Fuerza de Empuje (kp)</td>\n");
          bw.write("<td width = \"50%\">" + a.FuerzaEmpuje + "</td>");
          bw.write("</tr>\n");
          bw.write("<tr>\n");
          bw.write("<td width = \"50%\"><b>Tipo de propulsión/Número</td>\n");
          bw.write("<td width = \"50%\">" + a.getPropulsion() + "/" +
                   a.NumMotores + "</td>");
          bw.write("</tr>\n");

          bw.write("</table>\n");
          bw.write("</table>\n");
          bw.write("<div align=\"center\"><br>"
                   + "<a href=\"#INICIO\">INICIO</a><br>"
                   + "</div>");

        }
      }
      Fecha = new Date();
      SimpleDateFormat formato = new SimpleDateFormat("dd/MM/yyyy");

      bw.write("<hr>");
      bw.write("<center>Creado el &nbsp;" + formato.format(Fecha) +
               "</center>\n");
      bw.write("</body>\n");
      bw.write("</html>\n");
      bw.close();
      fw.close();
    }
    catch (java.io.IOException ex) {
      System.out.println("Excepcion de E/S:" + ex.getMessage());
    }
  }

  /*
      static byte[] cargaImagen(String imagen) {
          try {
              java.io.BufferedInputStream fr = new java.io.BufferedInputStream(Class.forName("").getClass().getResourceAsStream(imagen));
              byte[] buffer = new byte[fr.available()];
              fr.read(buffer,0, fr.available());
              fr.close();
              return buffer;
          }
          catch (java.io.IOException ex) {
              System.out.println("Excepcion E/S: "+ex.getMessage());
          }
          catch (java.lang.ClassNotFoundException ex) {
   System.out.println("Excepcion Clase  no encontrada: "+ex.getMessage());
          }
          return null;
      }

      static void salvaImagen(byte[] imagen, String direccion) {
          try {
   java.io.FileOutputStream fw = new java.io.FileOutputStream(direccion);
              fw.write(imagen,0,imagen.length-1);
              fw.close();
          }
          catch (java.io.IOException ex) {
              System.out.println("Excepcion E/S: "+ex.getMessage());
          }
      }
   */
}

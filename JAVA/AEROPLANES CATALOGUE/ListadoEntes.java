package aviones;

import java.util.ArrayList;
import java.util.Iterator;

/**
 * <p>Título: </p>
 * <p>Descripción: </p>
 * <p>Copyright: Copyright (c) 2004</p>
 * <p>Empresa: </p>
 * @author sin atribuir
 * @version 1.0
 */

public class ListadoEntes {

  public ArrayList VectorAviones;

  public ListadoEntes() {
    VectorAviones = new ArrayList();
  }

  public void añadir(Ente av) {
    VectorAviones.add(av);
  }

  /**
   *
   * @return String para añadir al HTML con marcadores de los aviones seleccionados
   */
  public String DameMarcadoresParse() {
    String marcadores = "";
    Iterator it = VectorAviones.iterator();
    while (it.hasNext()) {
      Object Obj = it.next();
      if (Obj.getClass().toString().equals("class aviones.Avion") && ((Ente)Obj).Seleccionado) {
        marcadores += "<a href=\"#" + ( (Avion) Obj).Modelo + "\">" +
            ( (Avion) Obj).Modelo + "</a>&nbsp;";
      }
    }
    return marcadores;

  }

  public boolean AlgunoSeleccionado() {
    Iterator it = VectorAviones.iterator();
    while (it.hasNext()) {
      Object Obj = it.next();
      if (Obj.getClass().toString().equals("class aviones.Avion") &&
          ( (Ente) Obj).Seleccionado) {
        return true;
      }
    }
    return false;
  }


}

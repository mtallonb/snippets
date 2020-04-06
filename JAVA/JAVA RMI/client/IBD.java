package palmirosoft;


import java.util.*;
import java.io.*;
import java.lang.*;
/**
 *
 * <p>Título: PFC: Peña de Futbol Control</p>
 * @version 2.6
 */
public interface IBD extends java.rmi.Remote {

public List obtenerDatosPenia()throws java.rmi.RemoteException;
//******************************************************************************
public List identificar(String s1, String s2)throws java.rmi.RemoteException;
//******************************************************************************
public boolean AnadirUsuarios(List datos)throws java.rmi.RemoteException;
//******************************************************************************
public List ListarUsuarios(String tipo)throws java.rmi.RemoteException;
//******************************************************************************
public boolean CambiarCargo(String login,String nueva)throws java.rmi.RemoteException;
//******************************************************************************
public boolean CambiarContrasena(String login,String nueva)throws java.rmi.RemoteException;
//******************************************************************************
//public boolean ModificarDatos(Usuario user)throws java.rmi.RemoteException;
//******************************************************************************
public boolean ModificarDatos(List user)throws java.rmi.RemoteException;
//******************************************************************************
public boolean BajaUsuario(String login,String pass)throws java.rmi.RemoteException;
//******************************************************************************
public List obtenerUsuario(String s1, int modo)throws java.rmi.RemoteException;
//******************************************************************************
//public boolean AnadirUsuarios(Usuario user)throws java.rmi.RemoteException;
//******************************************************************************
public boolean BajaDirectivo(String login)throws java.rmi.RemoteException;
//******************************************************************************
public void CambiarCuota(double c)throws java.rmi.RemoteException;
//******************************************************************************
public boolean Renovar(String us,int v)throws java.rmi.RemoteException;
//******************************************************************************
public void PendienteRenovacion(String us)throws java.rmi.RemoteException;
//******************************************************************************
public boolean EliminarUsuario(String id)throws java.rmi.RemoteException;
//******************************************************************************
public void PasarASocio(String id)throws java.rmi.RemoteException;
//******************************************************************************
public boolean PasarADirectivo(String id,String cargo)throws java.rmi.RemoteException;
//******************************************************************************
//******************************************************************************
//					METODO PROTOTIPO 2
//******************************************************************************
public int crearPartido(Partido p)throws java.rmi.RemoteException;
public void abrirPartido(int p)throws java.rmi.RemoteException;
public void cerrarPartido(int p)throws java.rmi.RemoteException;
public void abrirActividad(int p)throws java.rmi.RemoteException;
public void cerrarActividad(int p)throws java.rmi.RemoteException;
public Partido pruebaPartido(Partido p)throws java.rmi.RemoteException;

/**
 * Metodo de la base de datos que devuelve una coleccion de objetos Partido
 * @param f Fecha de los partidos, si es null se listan todos los partidos
 * @throws RemoteException
 * @return List
 */
public List listarPartidos(String f)throws java.rmi.RemoteException;

public boolean crearActividad(Actividad a)throws java.rmi.RemoteException;
public boolean crearReserva(Reserva r)throws java.rmi.RemoteException;
public void crearApuesta(Apuesta a)throws java.rmi.RemoteException;
public void crearNoticia(Noticia n)throws java.rmi.RemoteException;

public void modificarApuesta(Apuesta a)throws java.rmi.RemoteException;

public boolean borrarActividad(int id_ac)throws java.rmi.RemoteException;
public boolean borrarApuesta(int id_apuesta, int id_us)throws java.rmi.RemoteException;
public boolean borrarPartido(int id_partido)throws java.rmi.RemoteException;
public boolean borrarNoticia(int id_noticia)throws java.rmi.RemoteException;
public boolean borrarReserva(int id_ac,int id_us)throws java.rmi.RemoteException;

//public List listarApuestas(Partido p)throws java.rmi.RemoteException;
/**
 * Metodo de la Base de datos que devuelve todas las apuestas de un usuario. Si
  * id_usuario == -1 devolvera TODAS las apuesta en la BD
 * @param id_usuario int Es el DNI del usuario
 * @throws RemoteException
 * @return List
 */
public List listarApuestas(int id_p,int id_u)throws java.rmi.RemoteException;
public List listarActividades(String f)throws java.rmi.RemoteException;
/**
 * Metodo de la base de datos que devuelve un array de reservas
  * Establece un filtro de seleccion de reservas en funcion de la combinacion de los parametros
  * (-1,-1) Devuelve todas las reservas
  * (-1,dni) Devuelve todas las reservas del usuario
  * (id_actividad,-1) Devuelve todas las reservas para la actividad
  * (id_actividad,dni) Devuelve todas las reservas para la actividad y el usuario
 * @param a int Es el identificador de la actividad
 * @param id_usuario int Es el DNI del usuario
 * @throws RemoteException
 * @return List
 */
public List listarReservas(int a,int id_usuario)throws java.rmi.RemoteException;
public List listarNoticias()throws java.rmi.RemoteException;

public void guardarRdoPartido(Partido p)throws java.rmi.RemoteException;
public List obtenerApuntadosActividad(int id_actividad)throws java.rmi.RemoteException;
public boolean apuntarActividad(int i)throws java.rmi.RemoteException;
public List tipoActividad()throws java.rmi.RemoteException;
public List tipoCompeticiones()throws java.rmi.RemoteException;
public void insertarTipoActividad(String i)throws java.rmi.RemoteException;
public void admitirReservas(List rAdmitidas)throws java.rmi.RemoteException;


public List ListarValidacion(int caracter)throws java.rmi.RemoteException;
public void validarUsuarios(String id)throws java.rmi.RemoteException;
public void validarApuesta(int dni, int partido)throws java.rmi.RemoteException;
public void validarReserva(int dni,int actividad)throws java.rmi.RemoteException;
public void darBote(int id_partido)throws java.rmi.RemoteException;
public MiImagen getFoto(String foto)throws java.rmi.RemoteException;
}

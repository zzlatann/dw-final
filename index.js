//seleccionando elementos del DOM
const botonEnviar = document.getElementById("boton_enviar");
const formulario = document.querySelector("form");
const nombre = document.getElementById("nombre");
const edad = document.getElementById("edad");
const sueldo = document.getElementById("sueldo");
const posicion = document.getElementById("posicion");
const equipo = document.getElementById("equipo");
const biografia = document.getElementById("biografia");
const botonCancelar = document.getElementById("boton_cancelar");
const botonNuevo = document.getElementById("boton_nuevo");
const botonEditar = document.querySelectorAll(".editar");
const idContainer = document.querySelectorAll(".id_container");
const imagen = document.getElementById("imagen");

//obtener datos mediante la API
function obtenerDatosJugador(id){
    fetch(`jugadores/api.php?id=${id}`).
    then(response => response.json()).
    then(datos =>{
        nombre.value = datos.nombre;
        edad.value = datos.edad;
        sueldo.value = datos.sueldo;
        posicion.value = datos.posicion;
        equipo.value = datos.equipo;
        biografia.value = datos.biografia;
    }).catch(error => console.log("Error en la respuesta ",error));
}

//evento para el boton editar
if(botonEditar){
    botonEditar.forEach((elemento, indice )=>{
        const id = idContainer[indice].dataset.id;
        elemento.addEventListener("click",function(){
            formulario.classList.remove("oculto");
            obtenerDatosJugador(id);
            formulario.action = "jugadores/gestionar_jugador.php?accion=editar&id="+id;
            botonEnviar.textContent = "Actualizar jugador";
            imagen.required= false;
            botonNuevo.textContent = "Ocultar formulario";

    })});
}

//evento para el boton cancelar
botonCancelar.addEventListener("click",function(){
    nombre.value = "";
    edad.value = "";
    sueldo.value = "";
    posicion.value = "";
    equipo.value = "";
    biografia.value = "";
    formulario.action = "jugadores/gestionar_jugador.php?accion=agregar";
    botonEnviar.textContent = "Agregar jugador";
    imagen.required = true;
})

botonNuevo.addEventListener("click",function(){
    formulario.classList.toggle("oculto");
    if(botonNuevo.textContent === "Nuevo"){
        botonNuevo.textContent = "Ocultar Formulario";
    }else{
        botonNuevo.textContent = "Nuevo";
    }
})







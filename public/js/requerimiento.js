$("#btnagregar").on("click", function (e){

    $("#frmguardar")[0].reset();

    $('#prev').hide();
    $('#prev').removeAttr("src");

    $(".retirar").hide();

});

$(".retirar").on("click", function (e){


    $("#imn").val(null);

    $("#xy").val(null);

    $('#imag').hide();
    $('#imag').removeAttr("src");


    $('#prev').hide();
    $('#prev').removeAttr("src");


    $(".retirar").hide();

    });



    $('#imag').on("error", function(event) {
        $(event.target).css("display", "none");
    });


    const MAXIMO_TAMANIO_BYTES = 2000000; // 1MB = 1 millón de bytes


        function readImage (input)

        {
            if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function (e) {
                  $('.imagenPrevisualizacion').attr('src', e.target.result); // Renderizamos la imagen
              }
              reader.readAsDataURL(input.files[0]);
            }

          }

          $(".img").on("change", function ()

        {

        const MAXIMO_TAMANIO_BYTES = 2000000; // 1MB = 1 millón de bytes

            // Código a ejecutar cuando se detecta un cambio de archivo

            if (this.files.length <= 0) return;

            const archivo = this.files[0];



        if (archivo.size > MAXIMO_TAMANIO_BYTES)

        {

            const tamanioEnMb = MAXIMO_TAMANIO_BYTES / 1000000;
            alert(`El tamaño máximo es ${tamanioEnMb}  MB`);

            // Limpiar
            this.value = "";

        }

        else{

            $('#imag').show();
            $('#prev').show();

             $('.retirar').show();
            readImage(this);

        }

    }

    );




    $(".js-example-basic-multiple").select2();

    function cambioAvance() {
        document.getElementById("avan").innerHTML =
            document.getElementById("avance").value;
    }

    function inicio() {
        document
            .getElementById("avance")
            .addEventListener("change", cambioAvance, false);
    }

    addEventListener("load", inicio, false);


    $("#empresa").on("change", function (e) {

         $('#gerente').val(null).trigger('change');

        let valor = e.target.value;


        if (valor == "a") {
            $("#servicio").html(
                '<option value="a" >¡Seleccione una empresa!</option>'
            );

            $("#gerente").html(
                '<option value="a" >¡Seleccione una empresa!</option>'
            );
            return;
        }

        $.get("/requerimiento/" + valor + "/listado", function (data) {
            if (data.length == 0) {
                $("#servicio").html(
                    '<option value="a">No hay servicios ...</option>'
                );
            } else {
                var html_select = '<option value="a" >Seleccione ...</option>';

                for (var i = 0; i < data.length; ++i)
                    html_select +=
                        '<option value="' +
                        data[i].esid +
                        '">' +
                        data[i].snombre +
                        "</option>";

                $("#servicio").html(html_select);

                console.log(data.length);
                console.log(valor);
                console.log(data);
            }
        });

        $.get("/gerente/" + valor + "/listado", function (data) {
            if (data.length == 0) {
                $("#gerente").html(
                    '<option value="a">No hay gerentes ...</option>'
                );
            } else {
                var html_select =
                    '<option value="a" disabled>Seleccione ...</option>';

                for (var i = 0; i < data.length; ++i)
                    html_select +=
                        '<option value="' +
                        data[i].id +
                        '">' +
                        data[i].nombres +
                        " " +
                        data[i].apellidos +
                        "</option>";

                $("#gerente").html(html_select);

                console.log(data.length);
                console.log(valor);
                console.log(data);
            }
        });
    });

    const mensaje = document.getElementById("txtProblema");
    const mensaje2 = document.getElementById("txtDetalle");
    const mensaje3 = document.getElementById("txtDescripcion");

    const contador = document.getElementById("contador");
    const contador2 = document.getElementById("contador2");
    const contador3 = document.getElementById("contador3");

    mensaje.addEventListener("input", function (e) {
        const target = e.target;
        const longitudMax = target.getAttribute("maxlength");
        const longitudAct = target.value.length;
        contador.innerHTML = `${longitudAct}/${longitudMax}`;
    });

    mensaje2.addEventListener("input", function (e) {
        const target = e.target;
        const longitudMax = target.getAttribute("maxlength");
        const longitudAct = target.value.length;
        contador2.innerHTML = `${longitudAct}/${longitudMax}`;
    });

    mensaje3.addEventListener("input", function (e) {
        const target = e.target;
        const longitudMax = target.getAttribute("maxlength");
        const longitudAct = target.value.length;
        contador3.innerHTML = `${longitudAct}/${longitudMax}`;
    });

    /* ACA LA USO PARA HACER EL POST Y TRAER LA DATA AHORA SI ME ENTIUENDES ? */
    var datatable;

    function listar() {
        datatable = $("#requerimientos").DataTable({
            pageLength: 5,
            searching: false,
            ordering: true,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            dom: "Bfrtip",

            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json",
            },
            ajax: {
                url: "datatable/requerimientos",
                type: "GET",
                //   data : { '_token' : token_ },
                data: function (d) {
                    d._token = token_;
                    return $.extend({}, d, {
                        filters: {
                            nombre_empresa: $("#filtrosempre").val(),
                            estado: $("#filtros").val(),

                        },
                    });
                },
            },
            columns: [
                {
                    data: "usuario_que_registro",
                    orderable: false,
                    render: function (data, type, row, meta) {

                        if(data == $("#registro").val()){

                        return `<button type='button'  id='ButtonEditar' class='editar edit-modal btn btn-warning botonEditar'><span class='fa fa-edit'></span><span class='hidden-xs'> Editar</span></button>`;

                        }

                        else{

                        return `<button type='button'  id='ButtonEditar' class='editar edit-modal btn btn-warning botonEditar'><span class='fa fa-edit'></span><span class='hidden-xs'> Mostrar</span></button>`;

                        }

                    },
                },

                {
                    data: "estado_requerimiento",
                    orderable: false,
                    render: function (data, type, row, meta) {
                        if (data == "cancelado") {
                            return `<button type='button'  id='ButtonDesactivar' class='desactivar edit-modal btn btn-danger botonDesactivar' disabled><span class='fa fa-edit'></span><span class='hidden-xs'>Cancelar</span></button>`;
                        } else {
                            return `<button type='button'  id='ButtonDesactivar' class='desactivar edit-modal btn btn-danger botonDesactivar'><span class='fa fa-edit'></span><span class='hidden-xs'>Cancelar</span></button>`;
                        }
                    },
                },

                {
                    data: "id",
                    orderable: false,
                },

                {
                    data: "titulo_requerimiento",
                    orderable: false,
                },

                {
                    data: "nom_ape_solicitante",
                    orderable: false,
                },
                {
                    data: "encargados",
                    orderable: false,
                    render: function (data, type, row, meta) {
                        let encargados = data
                            .map((item) => {
                                return item.nom_ape;
                            })
                            .toString();
                        return `<span>${encargados}</span>`;
                    },
                },
                {
                    data: "asignados",
                    orderable: false,
                    render: function (data, type, row, meta) {
                        let asignados = data
                            .map((item) => {
                                return item.nom_ape;
                            })
                            .toString();
                        return `<span>${asignados}</span>`;
                    },
                },
                {
                    data: "nombre_empresa",
                    orderable: false,
                },
                {
                    data: "nombre_servicio",
                    orderable: false,
                },
                {
                    data: "avance_requerimiento",
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return `<div class="progress">
                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: ${data}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">${data}%</div>
                  </div>`;
                    },
                },
                {
                    data: "estado_requerimiento",
                    orderable: false,

                    render: function (data, type, row, meta) {
                        if (data == "pendiente") {
                            return '<span class="badge badge-warning">PENDIENTE</span>';
                        }

                        if (data == "en espera") {
                            return '<span class="badge badge-primary">EN ESPERA</span>';
                        }

                        if (data == "en proceso") {
                            return '<span class="badge badge-info">EN PROCESO</span>';
                        }

                        if (data == "culminado") {
                            return '<span class="badge badge-success">CULMINADO</span>';
                        }

                        if (data == "cancelado") {
                            return '<span class="badge badge-danger">CANCELADO</span>';
                        }
                    },
                },
                {
                    data: "prioridad_requerimiento",
                    orderable: false,

                    render: function (data, type, row, meta) {
                        if (data == "alta") {
                            return '<span class="badge badge-danger">ALTA</span>';
                        }

                        if (data == "media") {
                            return '<span class="badge badge-warning">MEDIA</span>';
                        } else if (data == "baja") {
                            return '<span class="badge badge-info">BAJA</span>';
                        }
                    },
                },
                {
                    data: "fecha_creacion",
                    orderable: true,
                    render: function (data, type, row, meta) {
                      return `${new Date(data).toLocaleDateString()} ${new Date(data).toLocaleTimeString('es-PE', { hour12: true })}`
                    },
                },
            ],
            order: [[9, "desc"]],
        });
    }



    $("#requerimientos").on("click", ".editar", function (event)
    {

        event.preventDefault();

        var data = datatable.row($(this).parents("tr")).data(); //Detecta a que fila hago click y me captura los datos en la variable data.
        if (datatable.row(this).child.isShown()) {
            //Cuando esta en tamaño responsive

            var data = datatable.row(this).data();
        }


        console.log(data);
        $("#idregistro").val(data["id"]);
        $("#editarTitulo").val(data["titulo_requerimiento"]);
        $("#editarDescripcion").val(data["descripcion_requerimiento"]);
        $("#UsuarioSolicitante").val(data["nom_ape_solicitante"]);
        $("#UsuarioSolicitante2").val(data["usuario_que_registro"]);
        $('#imn').val("");
         $('#imag').hide();
         $(".retirar").hide();
        $('#imag').removeAttr("src");

        if(data.imagen==0  || data.imagen==null){

        document.getElementById("mostimg").src = "vendor/adminlte/dist/img/sinimg.jpg";
        }

        else{

        document.getElementById("mostimg").src = "storage/"+data.imagen;

        }



        $("#UsuarioResponsable").val(data["encargados"].map((item) => {
            return item.nom_ape;
        })
        .toString());

        $("#avance").val(data["avance_requerimiento"]);
        $("#estado").val(data["estado_requerimiento"]);
        $("#prioridad").val(data["prioridad_requerimiento"]);


        // SI EL USUARIO LOGUEADO NO ES EL MISMO QUE HIZO EL REQUERIMIENTO, NO PODRÁ ACCEDER AL EDITAR

          if($("#registro").val() != $("#UsuarioSolicitante2").val())
        {

                $(".divoculto").hide();
        }

         else

        {
           $(".divoculto").show();
        }

        document.getElementById("avan").innerHTML =
        document.getElementById("avance").value;

        if (data["estado_requerimiento"] == "en proceso") {
            document.getElementById("elemento").removeAttribute("hidden");
            document.getElementById("trabajadores").removeAttribute("hidden");
        } else if (data["estado_requerimiento"] == "culminado") {
            document.getElementById("elemento").removeAttribute("hidden");
            document.getElementById("trabajadores").removeAttribute("hidden");
        } else {
            document.getElementById("elemento").setAttribute("hidden", "");
            document.getElementById("trabajadores").setAttribute("hidden", "");
        }

        $("#estado").on("change", function (e) {
            let estado = e.target.value;
            let el = document.getElementById("elemento");
            let tr = document.getElementById("trabajadores");

            if (estado == "pendiente" || estado == "en espera") {
                el.setAttribute("hidden", "");
                tr.setAttribute("hidden", "");
                $("#avance").val(0);

                cambioAvance();
            }

            if (estado == "en proceso") {
                el.removeAttribute("hidden");
                tr.removeAttribute("hidden");

                // $('#personal').val(null).trigger('change');
                $("#avance").val(0);
                cambioAvance();
            }

            if (estado == "culminado") {
                el.removeAttribute("hidden");
                tr.removeAttribute("hidden");

                $("#avance").val(100);
                cambioAvance();
            }
        });

        $("#avance").on("change", function (e) {
            let avance = e.target.value;

            if (avance == "100") {
                $("#estado").val("culminado");
            } else {
                $("#estado").val("en proceso");
            }
        });

        $.get("/personal/" + data["id_empresa"] + "/listado", function (dato) {
            if (dato.length == 0) {
                $("#personal").html(
                    '<option value="a">No hay colaboradores ...</option>'
                );
            } else {
                var html_select =
                    '<option value="a" disabled>Seleccione ...</option>';

                for (var i = 0; i < dato.length; ++i)
                    html_select +=
                        '<option value="' +
                        dato[i].id +
                        '">' +
                        dato[i].nombres +
                        " " +
                        dato[i].apellidos +
                        "</option>";

                $("#personal").html(html_select);

                console.log(dato.length);
                console.log(dato);

                $.get(`/requerimiento/${data["id"]}/getdetalle`, function (data) {
                    console.log(data);
                    $("#personal")
                        .val(data.map((item) => item.id))
                        .trigger("change");
                });
            }
        });

        $("#modaleditar").modal("show");
    });

    $("#btnguardar").on("click", (event) => {

        event.preventDefault();

        let route = $("#frmguardar").attr("action");

        /* let dataArray=$('#frmguardar').serialize() */
        let dataArray = new FormData($("#frmguardar")[0]);

        // dataArray.push({name:'_token',value:token_})
        console.log(dataArray);

        $.ajax({
            method: "POST",
            url: route,
            data: dataArray,
            cache: false,
            contentType: false,
            processData: false,

            success: function (Response) {
                if (Response == 1) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Datos guardados correctamente",
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    datatable.ajax.reload(null, false);
                    $("#frmguardar")[0].reset();
                    $("#prev")[0].setAttribute("src", "");

                    $("#modalagregar").modal("hide");
                } else {
                    alert("no guardado");
                }
            },
            error: (response) => {
                console.log(response);
                $.each(response.responseJSON.errors, function (key, value) {
                    response.responseJSON.errors[key].forEach((element) => {
                        console.log(element);
                        toastr.error(element);
                    });
                });
            },
        });


    });

    $("#btnactualizar").on("click", (event) => {

        event.preventDefault();


        let dataArray = $("#frmeditar").serializeArray();
        let route = "/requerimiento/" + dataArray[0].value;
        let _CSRF = { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') };
        var formData = new FormData($("#frmeditar")[0]);

        let valor =$("#mostimg").attr("src");

        let divisiones = valor.split("/", -2);

        let extraer =divisiones.slice(-1);

        formData.append("imganterior", extraer);
        formData.append("_method", 'PUT');


        let val = document.getElementById("personal").value;
        let val2 = document.getElementById("estado").value;



        if ((val.length == 0 && val2 == "en proceso") || val == "culminado") {
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "Si su estado es en proceso o culminado debe asignar personal.",
                showConfirmButton: false,
                timer: 2500,
            });
        } else {


            $.ajax({

                method: "post",
                url: route,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                headers: _CSRF,

                success: function (Response) {
                    if (Response == 1) {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Editado correctamente",
                            showConfirmButton: false,
                            timer: 1500,
                        });

                        datatable.ajax.reload(null, false);
                        $("#frmeditar")[0].reset();

                        $("#modaleditar").modal("hide");
                    } else {
                        alert("no editado");
                    }
                },
                error: (response) => {
                    console.log(response);
                    $.each(response.responseJSON.errors, function (key, value) {
                        response.responseJSON.errors[key].forEach((element) => {
                            console.log(element);
                            toastr.error(element);
                        });
                    });
                },
            });
        }


    });

    $("#colaboradores").on("click", ".desactivar", function () {
        Swal.fire({
            title: "¿Estás seguro(a)?",
            text: "¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Sí!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                var data = datatable.row($(this).parents("tr")).data();
                if (datatable.row(this).child.isShown()) {
                    var data = datatable.row(this).data();
                }

                console.log(data);
                let route = "/colaborador/" + data["id"];
                let data2 = {
                    id: data.id,
                    _token: token_,
                };

                $.ajax({
                    method: "delete",
                    url: route,
                    data: data2,

                    success: function (Response) {
                        if (Response == 1) {
                            Swal.fire(
                                "¡Desactivado!",
                                "Su registro ha sido desactivado.",
                                "success"
                            );

                            datatable.ajax.reload(null, false);
                        } else {
                            alert("no editado");
                        }
                    },
                    error: (response) => {
                        console.log(response);
                        $.each(response.responseJSON.errors, function (key, value) {
                            response.responseJSON.errors[key].forEach((element) => {
                                console.log(element);
                                toastr.error(element);
                            });
                        });
                    },
                });
            }
        });
    });

    $("#filtros").on("change", function (e) {
        datatable.ajax.reload(null, false);
    });

    $("#filtrosempre").on("change", function (e) {
        datatable.ajax.reload(null, false);
    });

    $("#requerimientos").on("click", ".desactivar", function () {
        Swal.fire({
            title: "¿Estás seguro(a)?",
            text: "¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Sí!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                var data = datatable.row($(this).parents("tr")).data();
                if (datatable.row(this).child.isShown()) {
                    var data = datatable.row(this).data();
                }

                console.log(data);
                let route = "/requerimiento/" + data["id"];
                let data2 = {
                    id: data.id,
                    _token: token_,
                };

                $.ajax({
                    method: "delete",
                    url: route,
                    data: data2,

                    success: function (Response) {
                        if (Response == 1) {
                            Swal.fire(
                                "Cancelado!",
                                "El requerimiento ha sido cancelado.",
                                "success"
                            );

                            datatable.ajax.reload(null, false);
                        } else {
                            alert("no editado");
                        }
                    },
                    error: (response) => {
                        console.log(response);
                        $.each(response.responseJSON.errors, function (key, value) {
                            response.responseJSON.errors[key].forEach((element) => {
                                console.log(element);
                                toastr.error(element);
                            });
                        });
                    },
                });
            }
        });
    });

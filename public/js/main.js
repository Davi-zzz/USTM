(function ($) {
    "use strict";

    $body = $("body");

    $(document).on({
        ajaxStart: function () {
            $body.addClass("loading");
        },
        ajaxStop: function () {
            $body.removeClass("loading");
        },
    });

    $("input[required], select[required], textarea[required]")
        .siblings("label")
        .addClass("required");

    $(".cep").mask("00000-000");
    $(".cpf").mask("000.000.000-00", {
        reverse: true,
    });
    $(".cnpj").mask("00.000.000/0000-00", {
        reverse: true,
    });

    $(".money").mask("0.000.000,00", {
        reverse: true,
    });

    $(".quantity").mask("0000,00", {
        reverse: true,
    });

    $(".code_bank").mask("000");

    $(".time").mask("00:00");

    $(".area").mask("0000000,0000", {
        reverse: true,
    });

    $("form").on("submit", function () {
        $(this).find(":submit").prop("disabled", true);
    });

    $(document).on("focus", ".money", function () {
        $(this).mask("0.000.000,00", {
            reverse: true,
        });
    });

    var cpfMascara = function (val) {
            return val.replace(/\D/g, "").length > 11
                ? "00.000.000/0000-00"
                : "000.000.000-009";
        },
        cpfOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(cpfMascara.apply({}, arguments), options);
            },
        };

    $(".cpf_cnpj").mask(cpfMascara, cpfOptions);

    var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, "").length === 11
                ? "(00) 00000-0000"
                : "(00) 0000-00009";
        },
        spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            },
        };

    $(".phone").mask(SPMaskBehavior, spOptions);

    $("#image").change(function () {
        var imgPath = $(this)[0].value;
        var ext = imgPath.substring(imgPath.lastIndexOf(".") + 1).toLowerCase();
        if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg")
            window.utilities.readUrl(this);
        else
            alert("Por favor, selecione o arquivo de imagem (jpg, jpeg, png).");
    });

    $(".select2").select2({
        language: "pt-BR",
    });

    $("#inp-city_id").select2({
        minimumInputLength: 3,
        language: "pt-BR",
        placeholder: "Selecione a Cidade",
        width: "100%",
        ajax: {
            cache: true,
            url: getUrl() + "/api/v1/cities/search",
            dataType: "json",
            data: function (params) {
                var query = {
                    search: params.term,
                };
                return query;
            },
            processResults: function (data) {
                var results = [];

                $.each(data, function (i, v) {
                    var o = {};
                    o.id = v.id;
                    o.text = v.title + " - " + v.letter;
                    o.value = v.id;
                    o.latitude = v.lat;
                    o.longitude = v.long;
                    results.push(o);
                });
                return {
                    results: results,
                };
            },
        },
    });

    $("#inp-profession_id").select2({
        minimumInputLength: 3,
        language: "pt-BR",
        placeholder: "Selecione...",
        width: "100%",
        ajax: {
            cache: true,
            url: getUrl() + "/api/v1/professions",
            dataType: "json",
            data: function (params) {
                var query = {
                    search: params.term,
                };
                return query;
            },
            processResults: function (data) {
                var results = [];

                $.each(data, function (i, v) {
                    var o = {};
                    o.id = v.id;
                    o.text = v.description;
                    o.value = v.id;
                    results.push(o);
                });
                return {
                    results: results,
                };
            },
        },
    });

    $("#inp-nif").on("change", function () {
        var nif = $(this).val();

        $.get(getUrl() + "/api/v1/get-person-by-nif?nif=" + nif).done(function (
            response
        ) {
            var person = response.data;

            if (person) {
                $("input")
                    .not($(this))
                    .each(function () {
                        if (person[$(this).attr("name")])
                            $(this).val(person[$(this).attr("name")]);
                    });

                $("#inp-city_id").append(
                    new Option(
                        person.city.title + "- " + person.city.state.letter,
                        person.city_id
                    )
                );
                $("#inp-city_id").val(person.city_id);
            }
        });
    });

    $(".btn-delete").on("click", function (e) {
        e.preventDefault();
        var form = $(this).parents("form").attr("id");
        swal({
            title: "Você está certo?",
            text:
                "Uma vez deletado, você não poderá recuperar esse item novamente!",
            icon: "warning",
            buttons: true,
            buttons: ["Cancelar", "Excluir"],
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                document.getElementById(form).submit();
            } else {
                swal("Este item está salvo!");
            }
        });
    });

    $(".btn-add").on("click", function () {
        var $table = $(this).closest(".row").prev().find(".table-dynamic");
        var $tr = $table.find(".dynamic-form");
        var $clone = $tr.clone();
        $clone.show();
        $clone.removeClass("dynamic-form");
        $clone.find("input,select").val("");
        $table.append($clone);
    });

    $(".multi-select").bootstrapDualListbox({
        nonSelectedListLabel: "Disponíveis",
        selectedListLabel: "Selecionados",
        filterPlaceHolder: "Filtrar",
        filterTextClear: "Mostrar Todos",
        moveSelectedLabel: "Mover Selecionados",
        moveAllLabel: "Mover Todos",
        removeSelectedLabel: "Remover Selecionado",
        removeAllLabel: "Remover Todos",
        infoText: "Mostrando Todos - {0}",
        infoTextFiltered:
            '<span class="label label-warning">Filtrado</span> {0} DE {1}',
        infoTextEmpty: "Sem Dados",
        moveOnSelect: false,
    });

    $(document).delegate(".btn-remove", "click", function (e) {
        e.preventDefault();
        swal({
            title: "Você esta certo?",
            text: "Deseja remover esse item mesmo?",
            icon: "warning",
            buttons: true,
        }).then((willDelete) => {
            if (willDelete) {
                if ($(this).closest("tr").hasClass("remove")) {
                    $(this).closest("tr").hide();
                    $(this).siblings("input").val(1);
                } else {
                    $(this).closest("tr").remove();
                }
            }
        });
    });
})(jQuery);
window.utilities = {
    changeImage: function () {
        $("#image").click();
    },
    readUrl: function (input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            reader.onload = function (e) {
                $("#preview-image").attr("src", e.target.result);
                $("#remove-image").val(0);
            };
        }
    },
    removeImage: function () {
        $("#preview-image").attr("src", "/img/noimage.png");
        $("#remove-image").val(1);
    },
};

function getUrl() {
    return document.getElementById("baseurl").value;
}

function convertMoedaToFloat(value) {
    if (!value) {
        return 0;
    }

    var number_without_mask = value.replace(".", "").replace(",", ".");
    return parseFloat(number_without_mask.replace(/[^0-9\.]+/g, ""));
}

function convertFloatToMoeda(value) {
    return value.toLocaleString("pt-BR", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

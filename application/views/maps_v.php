<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8"/>
<title>Карта</title>
<link href="css/style.css" rel="stylesheet"/>
<link href="/css/bootstrap.min.css" rel="stylesheet"/>
<link type="text/css" href="/js/css/smoothness/jquery-ui-1.10.4.custom.css" rel="Stylesheet"/>

<script src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>

<style>
    body {
        padding-top: 40px;
        background-color: #eee;
    }

    .form-signin {
        max-width: 450px;
        padding: 15px;
        margin: 0 auto;
    }

    #city {
        width: 300px;
    }

    #div_map {
        background-color: white;
        width: 600px;
        height: 450px;
    }

    .ui-helper-hidden-accessible {
        display: none;
    }

</style>
<script language="javascript">
    $(document).ready(function () {
        $("#view_maps").click(function () {
            city = $("#city").val();

            if (city == "" || city.length < 3) {
                alert("Введите название города (более 2-х символов)!");
                return false;
            }
            $("#static_maps").attr('src', '/img/loading.gif');

            $.ajax({
                url: "/getmap",
                type: "post",
                dataType: "json",
                data: {"city": city},
                success: function (response) {
                    if (response == "err-1") {
                        alert("Ошибка данных!");
                        $("#static_maps").attr('src', '');
                    } else if (response == "err-2") {
                        alert("Ошибка выборки города!");
                        $("#static_maps").attr('src', '');
                    } else if (response == "err-3") {
                        alert("Ошибка выборки точек!");
                        $("#static_maps").attr('src', '');
                    } else {
                        $("#static_maps").attr('src', response);
                    }
                }
            })
        });

        $("#city").autocomplete({
            source: function (request, response) {

                $("#static_maps").attr('src', '/img/loading.gif');
                $.ajax({
                    url: "/getcity",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {

                        $("#static_maps").attr('src', '');
                        if (data == "err-1") {
                            alert("Ошибка данных!");
                        } else if (data == "err-2") {
                            alert("Ошибка выборки!");
                        } else {
                            response(
                                $.map(data, function (item) {
                                    return {
                                        label: item.name + ", " + item.countryName,
                                        value: item.name
                                    }
                                })
                            );
                        }
                    }
                });
            },
            minLength: 3
        });

    });
</script>
</head>
<body>
<div class="container" align="center">
    <form class="form-signin" method="POST" action="">
        <input id="city" type="text" class="form-control ui-autocomplete-input" placeholder="Введите город" autofocus>

        <button class="butcls" type="button" id="view_maps">Показать</button>
    </form>
    <div align="center" id="div_map"><img id="static_maps" alt="" class="" src=""></div>
</div>
</body>
</html>
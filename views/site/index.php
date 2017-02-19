<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'My Yii Application';
$this->registerJs(<<<JS
    var socketUrl = '0.0.0.0:8080/coins';
    var coins = [];
    var sum = 0;
    var coinMap = {
        1: 200,
        2: 100,
        3: 50,
        4: 20,
        5: 10,
        6: 5
    };

    $('body').on('submit', '#coinform', function (e) {
        e.preventDefault();
        $.post( $('#coinform').attr('action'), $('#coinform').serialize(), function( data, hue, xhr ) {
            if (xhr.status == 202) {
                // TODO no coins entered
                alert(data.message);
            }
        }).error(function(data) {
            // TODO handle better
            alert(data);
        });
    });

    var mapCoin = function (value) {
        return coinMap[value];
    };

    function start(websocketServerLocation){
        var connection = new WebSocket('ws://' + websocketServerLocation);

        // When the connection is open, send some data to the server
        connection.onopen = function () {

        };

        // Log errors on connection open
        connection.onerror = function (error) {
            
        };

        // Log messages from the server
        connection.onmessage = function (e) {
            var coinValue = mapCoin(e.data);
            coins.push(coinValue);
            sum += parseInt(coinValue);
            var eur = sum / 100;
            $('#sum').text(eur.toFixed(2) + ' €');
        };
    }

    start(socketUrl);
    
JS
)
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Summa: <span id="sum">0.00 €</span> </h1>

        <form id="coinform" action="<?= Url::to(['/coin']) ?>" method="post">
            <div class="form-group">
                <label>Eesnimi</label>
                <input class="form-control" name="User[first_name]">
            </div>
            <div class="form-group">
                <label>Perekonnanimi</label>
                <input class="form-control" name="User[last_name]">
            </div>

            <button type="submit" class="btn btn-lg btn-success">Yes, send money!</button>
        </form>

    </div>
</div>

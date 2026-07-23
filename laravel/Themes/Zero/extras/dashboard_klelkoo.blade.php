<?php
declare(strict_types=1);
if (isset($_GET["db"])) {
    include dirname($_SERVER["DOCUMENT_ROOT"]) . "/laravel/Themes/KlekooAdm/Resources/views/admin/dashboard/db.php";
    exit;
}

include dirname($_SERVER["DOCUMENT_ROOT"]) . "/laravel/Themes/KlekooAdm/classes/LimeSurveyKK.php";

function obj_struct($obj)
{
    echo "<code>";
    echo "<b>Class:</b> " . get_class($obj) . "<br><br>";

    echo "<b>Methods</b><br><br>";
    $class_methods = get_class_methods($obj);
    asort($class_methods);
    foreach ($class_methods as $method_name) {
        echo $method_name ."<br>";
    }

    echo "<br><b>Properties</b><br><br>";
    $obj_properties = get_object_vars($obj);
    asort($obj_properties);
    foreach ($obj_properties as $name => $value) {
        echo $name . "<br>";
    }
    echo "</code>";
}

function json_change_key($array, $old_key, $new_key) {
	$json = str_replace('"' . $old_key . '":', '"' . $new_key . '":', json_encode($array));

	return json_decode($json);	
}

$customer = $profile->getProfile()->customers->first();
$sid = (isset($_REQUEST["sid"])) ? intval($_REQUEST["sid"]) : null;

if (!$sid) {
    $limeKK = new LimeSurveyKK(0);

    $all_surveys = $customer->surveyPdfs->all();
    foreach ($all_surveys as $tmp_survey) {
        $tmp_survey = $limeKK->db->selectOne("
            SELECT
                s.sid
            FROM
                " . $limeKK->tables[$limeKK->base_schema]["lime_surveys"] . " AS s
            WHERE
                s.sid = '" . $tmp_survey->survey_id . "'
            AND
                s.active = 'Y'
        ");
    
        if ($tmp_survey) {
            $sid = $tmp_survey->sid;
            break;
        }
    }

} else {
    $sid = $customer->surveyPdfs->where("survey_id", $sid);

    if (!$sid) {
        $sid = $customer->surveyPdfs->first()->survey_id;

    } else {
        $sid = $sid->first()->survey_id;
    }
}

$limeKK = new LimeSurveyKK($sid);
//$questions = $limeKK->get_questions();
$all_answers_grouped = $limeKK->get_all_answers_grouped();
?>


<style>
body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper, body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer, body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
    margin-left: 0 !important;
}
body:not(.layout-fixed) .main-sidebar {
    position: fixed !important;
}
.breadcrumb {
    display: none !important;
}
.main-header .nav-item i.fa-expand-arrows-alt {
    position: relative;
    top: 4.5px;
}
.survey_filter {
    display: block;
    float: left;
    margin:0 10px 0 5px;
}
.survey_filter select {
    height: 29px;
    padding: 3px 0px;
}
.survey_dates {
    display: block;
    float:left;
    margin: 0 5px;
}
.navbar_btn {
    padding: 2px 15px !important;
}
#survey_selector {
    margin: 5px 30px 0 7px;
}
.navitems_separator {
    margin-right: 18px !important;
    border-right: 1px solid #dcdcdc;
}
#navbar_logo {
    height: 41px;
    margin: 0 18px 0 7px;
}
#survey_selector select {
    padding: 3px 5px;
}
.dates_form {
    margin: 5px 5px 0;
    width: 100%;
    display: inline-block;
}
#charts_wrapper {
    margin-top:15px;
}
.charts {
    width: 100% !important;
}
.question_box {
    width: 100% !important;
    padding: 30px;
    margin: 0 0 15px;
    border: 1px solid #dddddd;
    border-radius: 3px;
    background: #fff;
}
.question_box .question {
    font-size:18px;
    font-weight: bold;
    line-height: 1.5em;
    min-height: 65px;
    margin:0 0 15px;
}
.question_box button {
    margin:0;
    padding: 0;
}
.question_box .card-header {
    background: transparent;
    margin:0;
    padding:0;
}
.chart_box {
    margin-bottom:30px;
}
.question_icon {
    width: 20px;
    vertical-align: top;
    padding-right: 10px;
}
.question_icon i {
    position: relative;
    top: 4.5px;
}
.card-header button table {
    width:100%;
}
.toggle_graph_box {
    text-align:center;
}
.toggle_graph_box .hide
, .toggle_graph_box .open {
    color:#999;
}
.toggle_graph_box .hide {
    display:none;
}
.statistycs
, .subtypes {
    text-align: center;
}
.statistycs .title {
    color:#6c757d;
    margin-top: 15px;
}
.statistycs .title i {
    margin-right: 10px;
}
.statistycs .number {
    display: block;
    margin-bottom: 10px;
    color:#000;
    font-family: "Montserrat", sans-serif;
    font-size:28px;
}
.subtypes {
    margin-bottom:10px;
}
.subtypes .subtype_title {
    display: inline-block;
    margin-right:10px;
}
.subtypes .subtype_number {
    font-family: "Montserrat", sans-serif;
    display: inline-block;
    color:#000;
}
.card.dash {
    padding-top: 50px;
    margin-top: 20px;
    margin-bottom: 40px;
}
.statistycs .number {
    font-size: 55px;
    padding-bottom: 50px;
    color: #003a7e;
}
.statistycs .title {
    font-size: 2rem;
}
.question_icon i {
    font-size: 30px;
}
.question_icon {
    width: 50px;
}
.main-header.navbar {
    padding:20px
}
.question_box.card {
    transition: all 0.1s linear;
    min-height: 200px;
    background-color: #e7e9f6;
}
.question_box.card:hover
, .question_box.card.open {
    background-color: #fff;
}
.accordion {
    margin-bottom: 20px;
}
.content-wrapper, body, .wrapper {
    background-color: #c8cedb;
}
.content-wrapper>.content {
    background-color: #c8cedb;
}
</style>
@extends('adm_theme::layouts.app')
@section('content')
<component :is="'script'">
jQuery("#survey_selector select").on("change", function() {
    window.location.href = jQuery("option:selected", this).val();
});

document.addEventListener("DOMContentLoaded", function() {
    Chart.register(ChartDataLabels);
});

jQuery(".card-header button").on("click", function() {
    if (jQuery(".hide", this).is(":hidden")) {
        jQuery(".hide", this).show();
        jQuery(".open", this).hide();
        jQuery(this).closest(".card").addClass("open");

    } else {
        jQuery(".hide", this).hide();
        jQuery(".open", this).show();
        jQuery(this).closest(".card").removeClass("open");
    }
});
</component>
<div id="charts_wrapper">
    <div class="row">
        <div class="col-md-4">
            <div class="card dash">
                <div class="row">
                    <div class="col-md-12">
                        <div class="statistycs">
                            <h5 class="title"><i class="fa fa-paper-plane" aria-hidden="true"></i>Sondaggi inviati</h5>
                            <span class="number"><?=number_format($all_answers_grouped["totals"]["sent"]["all"], 0, ",", "."); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row subtypes">
                    <div class="col-md-6">
                        <span class="subtype_title">SMS</span>
                        <span class="subtype_number"><?=number_format($all_answers_grouped["totals"]["sent"]["sms"], 0, ",", "."); ?></span>
                    </div>
                    <div class="col-md-6">
                        <span class="subtype_title">E-mail</span>
                        <span class="subtype_number"><?=number_format($all_answers_grouped["totals"]["sent"]["email"], 0, ",", "."); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dash">
                <div class="row">
                    <div class="col-md-12">
                        <div class="statistycs">
                            <h5 class="title"><i class="fa fa-pencil-alt" aria-hidden="true"></i>Totale risposte</h5>
                            <span class="number"><?=number_format($all_answers_grouped["totals"]["answers"]["all"], 0, ",", "."); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row subtypes">
                    <div class="col-md-6">
                        <span class="subtype_title">SMS</span>
                        <span class="subtype_number"><?=number_format($all_answers_grouped["totals"]["answers"]["sms"], 0, ",", "."); ?></span>
                    </div>
                    <div class="col-md-6">
                        <span class="subtype_title">E-mail</span>
                        <span class="subtype_number"><?=number_format($all_answers_grouped["totals"]["answers"]["email"], 0, ",", "."); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dash">
                <div class="row">
                    <div class="col-md-12">
                        <div class="statistycs">
                            <h5 class="title"><i class="fa fa-percent" aria-hidden="true"></i>Percentuale risposte</h5>
                            <?php
                            $tmp_sent_all = $all_answers_grouped["totals"]["sent"]["all"];
                            $percetage = ($tmp_sent_all) ? $all_answers_grouped["totals"]["answers"]["all"] * 100 / $tmp_sent_all : 0;
                            ?>
                            <span class="number"><?=number_format($percetage, 2, ",", "."); ?>%</span>
                        </div>
                    </div>
                </div>
                <div class="row subtypes">
                    <div class="col-md-6">
                        <?php
                        $tmp_answers_all = $all_answers_grouped["totals"]["answers"]["all"];
                        $percetage = ($tmp_answers_all) ? $all_answers_grouped["totals"]["answers"]["sms"] * 100 / $tmp_answers_all : 0;
                        ?>
                        <span class="subtype_title">SMS</span>
                        <span class="subtype_number"><?=number_format($percetage, 2, ",", "."); ?>%</span>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $percetage = ($tmp_answers_all) ? $all_answers_grouped["totals"]["answers"]["email"] * 100 / $tmp_answers_all : 0;
                        ?>
                        <span class="subtype_title">E-mail</span>
                        <span class="subtype_number"><?=number_format($percetage, 2, ",", "."); ?>%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @php
        $submissions = 0;
        foreach ($all_answers_grouped["answers_per_period"]["months"] as $month => $tmp_surveis) {
            foreach ($tmp_surveis as $tmp_survey) {
                $submissions++;
            }
        }

        $chart_types = array();
        $cn = 0;
        foreach ($all_answers_grouped["charts"] as $id => $chart) {
            $cn++;

            $chart_type = ($chart["chart_type"]) ?: $chart["question_chart_type"];
            if (!$chart_type || !is_array($chart["question"]) || !isset($chart["question"]["grouped"])) {
                continue;
            }

            $chart_types[$chart_type] = $chart_type;
            $values = $chart["question"];

            $take = abs($chart["record"]->take);
            $question_text = $values["text"];
            $grouped = $values["grouped"];
            ksort($grouped);
            @endphp
            <div id="accordion_{{$cn}}" class="col-md-6 col-sm-12 accordion">
                <div class="question_box card">
                    <div class="card-header" id="heading_{{$cn}}">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse_{{$cn}}" aria-expanded="true" aria-controls="collapse_{{$cn}}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="question_icon">
                                            @php
                                            $chart_icon = "";
                                            $icon_color = "#ddd";

                                            switch ($chart_type) {
                                                case "pieMonthBarWeekBar":
                                                    $chart_icon = "pie";
                                                    $icon_color = "#C0C0C0";
                                                    break;

                                                case "pieMonthBarWeekBarNoSi":
                                                case "pieMonthBarWeekBarSiNo":
                                                case "mixed:1":
                                                case "mixed:4":
                                                case "mixed:5":
                                                    $chart_icon = "pie";
                                                    $icon_color = "#36A2EB";
                                                break;

                                                case "mixed:9":
                                                    $chart_icon = "pie";
                                                    $icon_color = "#FF6384";
                                                break;

                                                case "horizbar1":
                                                    $chart_icon = "bar";
                                                    $icon_color = "#FF6384";
                                                break;

                                                case "lineHorizontalBar":
                                                    $chart_icon = "line";
                                                    $icon_color = "#3FC67A";
                                                break;
                                            }
                                            @endphp
                                            <i class="fa fa-chart-{{$chart_icon}}" style="color:{{$icon_color}};"></i>
                                        </td>
                                        <td>
                                            <h6 class="question" data-info="({{$id}} - {{$chart["record"]->question}} - {{$chart["record"]->question_type}} - {{$chart["record"]->answer_value}}) [{{$chart_type}} - {{$chart["chart_type"]}}]">
                                            {{$question_text}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="toggle_graph_box">
                                            <i class="fa fa-plus-circle open"></i>
                                            <i class="fa fa-minus-circle hide"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </h6>
                        </button>
                    </div>
                    <div id="collapse_{{$cn}}" class="collapse" aria-labelledby="heading_{{$cn}}" data-parent="#accordion_{{$cn}}">
                        <div class="card-body">
                            @php
                            switch ($chart_type) {
                                case "pieMonthBarWeekBar":
                                case "mixed:4":
                                    $chart_id = "chart_" . $id;
                                    $grouped_months = $values["months"];
                                    krsort($grouped_months);

                                    $vote_total = 0;
                                    $people_total = 0;
                                    $people_grand_total = 0;

                                    foreach ($grouped_months as $key => $answers) {
                                        foreach ($answers as $answer => $people) {
                                            $people_grand_total += $people;
                                            if ($answer != "Non saprei") {
                                                $people_total += $people;
                                                $vote_total += intval($answer) * $people;
                                            }
                                        }
                                    }

                                    // $not_answered = 0;
                                    // foreach ($all_answers_grouped["answers_per_record"] as $answers_per_record) {
                                    //     if (!isset($answers_per_record[$chart["record"]->question])) {
                                    //         $not_answered++;
                                    //     }
                                    // }

                                    $average = ($people_total) ? number_format($vote_total / $people_total, 2) : 0;
                                    $rest = 10 - $average;
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                                
                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: "doughnut"
                                                            , data: {
                                                                labels: false
                                                                , datasets: [{
                                                                    label: [
                                                                        'Media'
                                                                    ]
                                                                    , data: [
                                                                        "{{$rest}}"
                                                                        , "{{$average}}"
                                                                    ]
                                                                    , backgroundColor: [
                                                                        "rgba(0, 0, 0, 0)"
                                                                        , "rgba(192, 192, 192, 1)"
                                                                    ]
                                                                }]
                                                            }
                                                            , options: {
                                                                plugins: {
                                                                    title: {
                                                                        display: true
                                                                        , text: [
                                                                            "TOTALE RISPONDENTI {{$people_grand_total}}"
                                                                            @php
                                                                            if ($chart["record"]->question_type == "BT") {
                                                                                echo ', "NON RISPONDENTI ' . ($submissions - $people_grand_total) . '"';
                                                                            }
                                                                            @endphp
                                                                        ]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: false
                                                                }
                                                            }
                                                            , plugins: [{
                                                                id: "centerText"
                                                                , afterDatasetsDraw(chart, args, options) {
                                                                    const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;
                        
                                                                    ctx.save();
                        
                                                                    var fontSize = width * 4.5 / 100;
                                                                    var lineHeight = fontSize + (fontSize * {{$take}} / 100);
                        
                                                                    ctx.font = "bolder " + fontSize + "px Arial";
                                                                    ctx.fillStyle = "rgba(0, 0, 0, 1)";
                                                                    ctx.textAlign = "center";
                                                                    ctx.fillText("{{$average}}", width / 2, (height / 2 + top - (lineHeight)));
                                                                    ctx.restore();
                        
                                                                    ctx.font = fontSize + "px Arial";
                                                                    ctx.fillStyle = "rgba(0, 0, 0, 1)";
                                                                    ctx.textAlign = "center";
                                                                    ctx.fillText("MEDIA", width / 2, (height / 2 + top) + fontSize - lineHeight);
                                                                    ctx.restore();
                        
                                                                    ctx.font = fontSize + "px Arial";
                                                                    ctx.fillStyle = "rgba(0, 0, 0, 1)";
                                                                    ctx.textAlign = "center";
                                                                    ctx.fillText("COMPLESSIVA", width / 2, (height / 2 + top) + fontSize);
                                                                    ctx.restore();
                                                                }
                                                            }]
                                                        });
                                                    });
                                                </component>
                                            </div>
                                        </div>
                                        @php
                                        
                                        $chart_id = "chart_month_" . $id;
                                        $grouped_months = $values["months"];
                                        krsort($grouped_months);

                                        $people_per_step = array();
                                        $data = array();
                                        $n = 0;
                                        foreach ($grouped_months as $key => $answers) {
                                            $n++;
                                            $vote_total = 0;
                                            $people_total = 0;
                                            foreach ($answers as $answer => $people) {
                                                $vote_total += intval($answer) * $people;
                                                $people_total += $people;
                                            }
                                            $people_total = $people_total - ((array_key_exists("Non saprei", $answers)) ? $answers["Non saprei"] : 0);
                                            $people_per_step[$key] = $key . ";" . $people_total;
                                            $average = ($people_total) ? number_format($vote_total / $people_total, 2) : 0;
                                            
                                            $data[$key] = $average;

                                            if ($n == $take) {
                                                break;
                                            }
                                        }
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                                
                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: 'bar',
                                                            data: {
                                                                labels: [
                                                                    @php   
                                                                    echo "'" . implode("', '", $people_per_step) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: 'Media'
                                                                    , data: [
                                                                        @php   
                                                                        echo "'" . implode("', '", $data) . "'";
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        'rgba(255, 99, 132, 0.2)'
                                                                    ]
                                                                    , borderColor: [
                                                                        'rgba(255, 99, 132, 1)'
                                                                    ]
                                                                    , borderWidth: 1
                                                                }]
                                                            }
                                                            , options: {
                                                                indexAxis: "x"
                                                                , scales: {
                                                                    x: {
                                                                        reverse: true
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);
                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return step;
                                                                            }
                                                                        }
                                                                    }
                                                                    , xAxis2: {
                                                                        position: "top"
                                                                        , reverse: true
                                                                        , grid: {
                                                                            drawOnChartArea: false
                                                                        }
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);

                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return people;
                                                                            }
                                                                        }
                                                                    }
                                                                    , y: {
                                                                        type: "linear"
                                                                        , beginAtZero: true
                                                                        , min: 0
                                                                        , max: 10
                                                                        , ticks: {
                                                                            stepSize: 1
                                                                        }
                                                                    }
                                                                }
                                                                , plugins: {
                                                                    legend: {
                                                                        display: false
                                                                    }
                                                                    , title: {
                                                                        display: true
                                                                        , text: ["ULTIMI {{$take}} MESI"]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                            @php
                                            $chart_id = "chart_week_" . $id;
                                            $grouped_weeks = $values["weeks"];
                                            krsort($grouped_weeks);

                                            $people_per_step = array();
                                            $data = array();
                                            $n = 0;
                                            foreach ($grouped_weeks as $key => $answers) {
                                                $n++;
                                                $vote_total = 0;
                                                $people_total = 0;
                                                foreach ($answers as $answer => $people) {
                                                    $vote_total += intval($answer) * $people;
                                                    $people_total += $people;
                                                }
                                                $people_total = $people_total - ((array_key_exists("Non saprei", $answers)) ? $answers["Non saprei"] : 0);
                                                $people_per_step[$key] = $key . ";" . $people_total;
                                                $average = ($people_total) ? number_format($vote_total / $people_total, 2) : 0;
                                                
                                                $data[$key] = $average;

                                                if ($n == $take) {
                                                    break;
                                                }
                                            }
                                            @endphp
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                                
                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: 'bar',
                                                            data: {
                                                                labels: [
                                                                    @php   
                                                                    echo "'" . implode("', '", $people_per_step) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: 'Media'
                                                                    , data: [
                                                                        @php   
                                                                        echo "'" . implode("', '", $data) . "'";
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        'rgba(255, 99, 132, 0.2)'
                                                                    ]
                                                                    , borderColor: [
                                                                        'rgba(255, 99, 132, 1)'
                                                                    ]
                                                                    , borderWidth: 1
                                                                }]
                                                            }
                                                            , options: {
                                                                indexAxis: 'x'
                                                                , scales: {
                                                                    x: {
                                                                        reverse: true
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);
                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return step;
                                                                            }
                                                                        }
                                                                    }
                                                                    , xAxis2: {
                                                                        position: "top"
                                                                        , reverse: true
                                                                        , grid: {
                                                                            drawOnChartArea: false
                                                                        }
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);

                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return people;
                                                                            }
                                                                        }
                                                                    }
                                                                    , y: {
                                                                        type: "linear"
                                                                        , beginAtZero: true
                                                                        , min: 0
                                                                        , max: 10
                                                                        , ticks: {
                                                                            stepSize: 1
                                                                        }
                                                                    }
                                                                }
                                                                , plugins: {
                                                                    legend: {
                                                                        display: false
                                                                    }
                                                                    , title: {
                                                                        display: true
                                                                        , text: ["ULTIME {{$take}} SETTIMANE"]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    break;

                                case "pieMonthBarWeekBarNoSi":
                                    $chart_id = "chart_" . $id;
                                    $keys = array_keys($grouped);
                                    $grouped_months = $values["months"];
                                    krsort($grouped_months);

                                    $people_grand_total = 0;
                                    $no = "N";
                                    $yes = "Y";
                                    if (in_array($chart["record"]->answer, array("Sì", "No"))) {
                                        $no = "No";
                                        $yes = "Sì";
                                    }
                                    $votes_total = array(
                                        $no => 0
                                        , $yes => 0
                                        , "Non so / Non risponde" => 0
                                    );
                                    
                                    $n = 0;
                                    foreach ($grouped_months as $key => $answers) {
                                        $n++;

                                        //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                        $people_total = 0;
                                        foreach ($answers as $answer => $people) {
                                            $votes_total[$answer] += $people;
                                            if ($answer != "Non so / Non risponde") {
                                                $people_total += $people;
                                            }
                                        }
                                        $people_grand_total += $people_total;
                                    }

                                    $no_percent = 0;
                                    $yes_percent = 0;
                                    if ($people_grand_total) {
                                        $no_percent = number_format($votes_total[$no] * 100 / $people_grand_total, 1);
                                        $yes_percent = number_format($votes_total[$yes] * 100 / $people_grand_total, 1);
                                    }
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                                                        
                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: "doughnut"
                                                            , data: {
                                                                labels: [
                                                                    @php
                                                                    echo "'" . implode("', '", $keys) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: "Rispondenti"
                                                                    , data: [
                                                                        "{{$yes_percent}}"
                                                                        , "{{$no_percent}}"
                                                                    ]
                                                                    , backgroundColor: [
                                                                        "rgba(255, 99, 132, 1)"
                                                                        , "rgba(54, 162, 235, 1)"
                                                                    ]
                                                                }]
                                                            }
                                                            , options: {
                                                                plugins: {
                                                                    title: {
                                                                        display: true
                                                                        , text: [
                                                                            "TOTALE RISPONDENTI {{$people_grand_total}}"
                                                                            @php
                                                                            if ($chart["record"]->question_type == "BT") {
                                                                                echo ', "NON RISPONDENTI ' . ($submissions - $people_grand_total) . '"';
                                                                            }
                                                                            @endphp
                                                                        ]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        color: "#ffffff"
                                                                        , formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                        </div>
                                        @php
                                        
                                        $chart_id = "chart_month_" . $id;
                                        $grouped_months = $values["months"];
                                        krsort($grouped_months);

                                        $people_per_step = array();
                                        $data = array();
                                        $n = 0;

                                        foreach ($grouped_months as $key => $answers) {
                                            $n++;

                                            //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                            $votes = array(
                                                $no => 0
                                                , $yes => 0
                                                , "Non so / Non risponde" => 0
                                            );

                                            $people_total = 0;
                                            foreach ($answers as $answer => $people) {
                                                $votes[$answer] += $people;
                                                if ($answer != "Non so / Non risponde") {
                                                    $people_total += $people;
                                                }
                                            }
                                            $people_per_step[$key] = $key . ";" . $votes[$no];

                                            $percentage = ($people_total) ? number_format($votes[$no] * 100 / $people_total, 1) : 0;
                                            
                                            $data[$key] = $percentage;

                                            if ($n == $take) {
                                                break;
                                            }
                                        }
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');

                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: 'bar'
                                                            , data: {
                                                                labels: [
                                                                    @php   
                                                                    echo "'" . implode("', '", $people_per_step) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: 'Media'
                                                                    , data: [
                                                                        @php   
                                                                        echo "'" . implode("', '", $data) . "'";
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        'rgba(255, 99, 132, 0.2)'
                                                                    ]
                                                                    , borderColor: [
                                                                        'rgba(255, 99, 132, 1)'
                                                                    ]
                                                                    , borderWidth: 1
                                                                }]
                                                            }
                                                            , options: {
                                                                indexAxis: "x"
                                                                , scales: {
                                                                    x: {
                                                                        reverse: true
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);
                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return step;
                                                                            }
                                                                        }
                                                                    }
                                                                    , xAxis2: {
                                                                        position: "top"
                                                                        , reverse: true
                                                                        , grid: {
                                                                            drawOnChartArea: false
                                                                        }
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);

                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return people;
                                                                            }
                                                                        }
                                                                    }
                                                                    , y: {
                                                                        type: "linear"
                                                                        , beginAtZero: true
                                                                        , min: 0
                                                                        , max: 100
                                                                        , ticks: {
                                                                            stepSize: 10
                                                                        }
                                                                    }
                                                                }
                                                                , plugins: {
                                                                    legend: {
                                                                        display: false
                                                                    }
                                                                    , title: {
                                                                        display: true
                                                                        , text: ["ULTIMI {{$take}} MESI"]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                            @php
                                            $chart_id = "chart_week_" . $id;
                                            $grouped_weeks = $values["weeks"];
                                            krsort($grouped_weeks);

                                            $people_per_step = array();
                                            $data = array();
                                            $n = 0;
                                            foreach ($grouped_weeks as $key => $answers) {
                                                $n++;

                                                //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                                $votes = array(
                                                    $no => 0
                                                    , $yes => 0
                                                    , "Non so / Non risponde" => 0
                                                );

                                                $people_total = 0;
                                                foreach ($answers as $answer => $people) {
                                                    $votes[$answer] += $people;
                                                    if ($answer != "Non so / Non risponde") {
                                                        $people_total += $people;
                                                    }
                                                }
                                                $people_per_step[$key] = $key . ";" . $votes[$no];

                                                $percentage = ($people_total) ? number_format($votes[$no] * 100 / $people_total, 1) : 0;
                                                
                                                $data[$key] = $percentage;

                                                if ($n == $take) {
                                                    break;
                                                }
                                            }
                                            @endphp
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');

                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: 'bar'
                                                            , data: {
                                                                labels: [
                                                                    @php   
                                                                    echo "'" . implode("', '", $people_per_step) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: 'Media'
                                                                    , data: [
                                                                        @php   
                                                                        echo "'" . implode("', '", $data) . "'";
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        'rgba(255, 99, 132, 0.2)'
                                                                    ]
                                                                    , borderColor: [
                                                                        'rgba(255, 99, 132, 1)'
                                                                    ]
                                                                    , borderWidth: 1
                                                                }]
                                                            }
                                                            , options: {
                                                                indexAxis: "x"
                                                                , scales: {
                                                                    x: {
                                                                        reverse: true
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);
                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return step;
                                                                            }
                                                                        }
                                                                    }
                                                                    , xAxis2: {
                                                                        position: "top"
                                                                        , reverse: true
                                                                        , grid: {
                                                                            drawOnChartArea: false
                                                                        }
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);

                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return people;
                                                                            }
                                                                        }
                                                                    }
                                                                    , y: {
                                                                        type: "linear"
                                                                        , beginAtZero: true
                                                                        , min: 0
                                                                        , max: 100
                                                                        , ticks: {
                                                                            stepSize: 10
                                                                        }
                                                                    }
                                                                }
                                                                , plugins: {
                                                                    legend: {
                                                                        display: false
                                                                    }
                                                                    , title: {
                                                                        display: true
                                                                        , text: ["ULTIME {{$take}} SETTIMANE"]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    break;

                                case "pieMonthBarWeekBarSiNo":                        
                                    $chart_id = "chart_" . $id;
                                    $keys = array_keys(array_reverse($grouped));
                                    $grouped_months = $values["months"];
                                    krsort($grouped_months);

                                    $people_grand_total = 0;
                                    $no = "N";
                                    $yes = "Y";
                                    if (in_array($chart["record"]->answer, array("Sì", "No"))) {
                                        $no = "No";
                                        $yes = "Sì";
                                    }
                                    $votes_total = array(
                                        $no => 0
                                        , $yes => 0
                                        , "Non so / Non risponde" => 0
                                    );
                                    
                                    $n = 0;
                                    foreach ($grouped_months as $key => $answers) {
                                        $n++;

                                        //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                        $people_total = 0;
                                        foreach ($answers as $answer => $people) {
                                            $votes_total[$answer] += $people;
                                            if ($answer != "Non so / Non risponde") {
                                                $people_total += $people;
                                            }
                                        }
                                        $people_grand_total += $people_total;
                                    }

                                    $no_percent = 0;
                                    $yes_percent = 0;
                                    if ($people_grand_total) {
                                        $no_percent = number_format($votes_total[$no] * 100 / $people_grand_total, 1);
                                        $yes_percent = number_format($votes_total[$yes] * 100 / $people_grand_total, 1);
                                    }
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                                                        
                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: "doughnut"
                                                            , data: {
                                                                labels: [
                                                                    @php
                                                                    echo "'" . implode("', '", $keys) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: "Rispondenti"
                                                                    , data: [
                                                                        "{{$yes_percent}}"
                                                                        , "{{$no_percent}}"
                                                                    ]
                                                                    , backgroundColor: [
                                                                        "rgba(255, 99, 132, 1)"
                                                                        , "rgba(54, 162, 235, 1)"
                                                                    ]
                                                                }]
                                                            }
                                                            , options: {
                                                                plugins: {
                                                                    title: {
                                                                        display: true
                                                                        , text: [
                                                                            "TOTALE RISPONDENTI {{$people_grand_total}}"
                                                                            @php
                                                                            if ($chart["record"]->question_type == "BT") {
                                                                                echo ', "NON RISPONDENTI ' . ($submissions - $people_grand_total) . '"';
                                                                            }
                                                                            @endphp
                                                                        ]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        color: "#ffffff"
                                                                        , formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                        </div>
                                        @php
                                        
                                        $chart_id = "chart_month_" . $id;
                                        $grouped_months = $values["months"];
                                        krsort($grouped_months);

                                        $people_per_step = array();
                                        $data = array();
                                        $n = 0;

                                        foreach ($grouped_months as $key => $answers) {
                                            $n++;

                                            //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                            $votes = array(
                                                $no => 0
                                                , $yes => 0
                                                , "Non so / Non risponde" => 0
                                            );

                                            $people_total = 0;
                                            foreach ($answers as $answer => $people) {
                                                $votes[$answer] += $people;
                                                if ($answer != "Non so / Non risponde") {
                                                    $people_total += $people;
                                                }
                                            }

                                            $answer_value = ($chart["record"]->answer) ? $chart["record"]->answer : $yes;
                                            $people_per_step[$key] = $key . ";" . $votes[$answer_value];
                                            $percentage = ($people_total) ? number_format($votes[$answer_value] * 100 / $people_total, 1) : 0;
                                            
                                            $data[$key] = $percentage;

                                            if ($n == $take) {
                                                break;
                                            }
                                        }
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');

                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: 'bar'
                                                            , data: {
                                                                labels: [
                                                                    @php   
                                                                    echo "'" . implode("', '", $people_per_step) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: 'Media'
                                                                    , data: [
                                                                        @php   
                                                                        echo "'" . implode("', '", $data) . "'";
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        'rgba(255, 99, 132, 0.2)'
                                                                    ]
                                                                    , borderColor: [
                                                                        'rgba(255, 99, 132, 1)'
                                                                    ]
                                                                    , borderWidth: 1
                                                                }]
                                                            }
                                                            , options: {
                                                                indexAxis: "x"
                                                                , scales: {
                                                                    x: {
                                                                        reverse: true
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);
                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return step;
                                                                            }
                                                                        }
                                                                    }
                                                                    , xAxis2: {
                                                                        position: "top"
                                                                        , reverse: true
                                                                        , grid: {
                                                                            drawOnChartArea: false
                                                                        }
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);

                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return people;
                                                                            }
                                                                        }
                                                                    }
                                                                    , y: {
                                                                        type: "linear"
                                                                        , beginAtZero: true
                                                                        , min: 0
                                                                        , max: 100
                                                                        , ticks: {
                                                                            stepSize: 10
                                                                        }
                                                                    }
                                                                }
                                                                , plugins: {
                                                                    legend: {
                                                                        display: false
                                                                    }
                                                                    , title: {
                                                                        display: true
                                                                        , text: ["ULTIMI {{$take}} MESI"]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                            @php
                                            $chart_id = "chart_week_" . $id;
                                            $grouped_weeks = $values["weeks"];
                                            krsort($grouped_weeks);

                                            $people_per_step = array();
                                            $data = array();
                                            $n = 0;
                                            foreach ($grouped_weeks as $key => $answers) {
                                                $n++;

                                                //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                                $votes = array(
                                                    $no => 0
                                                    , $yes => 0
                                                    , "Non so / Non risponde" => 0
                                                );

                                                $people_total = 0;
                                                foreach ($answers as $answer => $people) {
                                                    $votes[$answer] += $people;
                                                    if ($answer != "Non so / Non risponde") {
                                                        $people_total += $people;
                                                    }
                                                }
                                                $people_per_step[$key] = $key . ";" . $votes[$answer_value];

                                                $percentage = ($people_total) ? number_format($votes[$answer_value] * 100 / $people_total, 1) : 0;
                                                
                                                $data[$key] = $percentage;

                                                if ($n == $take) {
                                                    break;
                                                }
                                            }
                                            @endphp
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');

                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: 'bar'
                                                            , data: {
                                                                labels: [
                                                                    @php   
                                                                    echo "'" . implode("', '", $people_per_step) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: 'Media'
                                                                    , data: [
                                                                        @php   
                                                                        echo "'" . implode("', '", $data) . "'";
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        'rgba(255, 99, 132, 0.2)'
                                                                    ]
                                                                    , borderColor: [
                                                                        'rgba(255, 99, 132, 1)'
                                                                    ]
                                                                    , borderWidth: 1
                                                                }]
                                                            }
                                                            , options: {
                                                                indexAxis: "x"
                                                                , scales: {
                                                                    x: {
                                                                        reverse: true
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);
                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return step;
                                                                            }
                                                                        }
                                                                    }
                                                                    , xAxis2: {
                                                                        position: "top"
                                                                        , reverse: true
                                                                        , grid: {
                                                                            drawOnChartArea: false
                                                                        }
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);

                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return people;
                                                                            }
                                                                        }
                                                                    }
                                                                    , y: {
                                                                        type: "linear"
                                                                        , beginAtZero: true
                                                                        , min: 0
                                                                        , max: 100
                                                                        , ticks: {
                                                                            stepSize: 10
                                                                        }
                                                                    }
                                                                }
                                                                , plugins: {
                                                                    legend: {
                                                                        display: false
                                                                    }
                                                                    , title: {
                                                                        display: true
                                                                        , text: ["ULTIME {{$take}} SETTIMANE"]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    break;
                                
                                case "horizbar1":
                                    $chart_id = "chart_" . $id;
                                    
                                    $keys = array();
                                    $grouped_months = array();
                                    $months_values = array();
                                    if (!$values["grouped"]) {
                                        foreach ($values["subquestions"] as $key => $tmp_values) {
                                            if (!$tmp_values) {
                                                continue;
                                            }

                                            foreach ($tmp_values["months"] as $key => $answers) {
                                                if (!array_key_exists($key, $months_values)) {
                                                    $months_values[$key] = array();
                                                }

                                                if (!array_key_exists($tmp_values["text"], $months_values[$key])) {
                                                    $months_values[$key][$tmp_values["text"]] = 0;
                                                    $keys[$tmp_values["text"]] = $tmp_values["text"];
                                                }

                                                $months_values[$key][$tmp_values["text"]] += $answers[array_key_first($answers)];
                                            }
                                        }

                                        $grouped_months = $months_values;
                                        $keys = array_keys($keys);

                                    } else {
                                        $grouped_months = $values["months"];
                                        $keys = array_keys($grouped);
                                    }

                                    krsort($grouped_months);

                                    $grouped = array();
                                    foreach ($keys as $key) {
                                        $grouped[$key] = 0;
                                    }

                                    $people_total = 0;
                                    $n = 0;
                                    foreach ($grouped_months as $key => $answers) {
                                        $n++;

                                        foreach ($answers as $answer => $people) {
                                            $grouped[$answer] += $people;
                                            $people_total += $people;
                                        }
                                    }

                                    $surveys_total = $people_total;
                                    if ($chart["record"]->question_type == "M") {
                                        $surveys_total = 0;
                                        foreach ($all_answers_grouped["answers_per_period"]["months"] as $month => $tmp_surveis) {
                                            foreach ($tmp_surveis as $tmp_survey) {
                                                $surveys_total++;
                                            }
                                        }
                                    }

                                    foreach ($grouped as $key => $value) {
                                        $grouped[$key] = ($value) ? number_format($value * 100 / $surveys_total, 1) : 0;
                                    }

                                    arsort($grouped);
                                    
                                    $height = (count($grouped) + 1.5) * 15;
                                    @endphp
                                    <div class="chart_box">
                                        <canvas class="charts" height="{{$height}}" id="{{$chart_id}}"></canvas>
                                        <component :is="'script'">
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                        
                                                const {{$chart_id}} = new Chart(ctx, {
                                                    type: 'bar'
                                                    , data: {
                                                        labels: [
                                                            @php
                                                            echo "'" . implode("', '", array_keys($grouped)) . "'";
                                                            @endphp
                                                        ]
                                                        , datasets: [{
                                                            label: 'Rispondenti'
                                                            , data: [
                                                                @php   
                                                                echo "'" . implode("', '", $grouped) . "'";
                                                                @endphp
                                                            ]
                                                            , backgroundColor: [
                                                                'rgba(255, 99, 132, 0.2)'
                                                            ]
                                                            , borderColor: [
                                                                'rgba(255, 99, 132, 1)'
                                                            ]
                                                            , borderWidth: 1
                                                        }]
                                                    }
                                                    , options: {
                                                        indexAxis: 'y'
                                                        , scales: {
                                                            y: {
                                                                type: "category"
                                                                , ticks: {
                                                                    autoSkip: false
                                                                }
                                                            }
                                                        }
                                                        , plugins: {
                                                            legend: {
                                                                display: false
                                                            }
                                                            , title: {
                                                                display: true
                                                                , text: [
                                                                    "TOTALE RISPONDENTI {{$people_total}}"
                                                                    @php
                                                                    if ($chart["record"]->question_type == "BT") {
                                                                        echo ', "NON RISPONDENTI ' . ($submissions - $people_grand_total) . '"';
                                                                    }
                                                                    @endphp
                                                                ]
                                                                , font: {
                                                                    size: 14
                                                                }
                                                            }
                                                            , datalabels: {
                                                                formatter: (value, ctx) => {
                                                                    return value + " %";
                                                                }
                                                                , anchor: "end"
                                                                , align: "right"
                                                                , clamp: true
                                                            }
                                                        }
                                                    }
                                                });
                                            });
                                        </component>
                                    </div>
                                    @php
                                    break;

                                case "lineHorizontalBar":
                                    $chart_id = "chart_" . $id;

                                    $people_total = 0;
                                    $grouped_months = array();
                                    $n = 0;
                                    if ($chart["record"]->subquestion) {
                                        foreach($all_answers_grouped["answers_per_period"]["months"] as $month => $surveys) {
                                            $n++;

                                            if (!isset($grouped_months[$month])) {
                                                $grouped_months[$month] = array();
                                            }
                                            
                                            foreach ($surveys as $survey_id => $survey) {
                                                if (isset($survey[$chart["record"]->subquestion])) {
                                                    $sq_value = $survey[$chart["record"]->subquestion];
                                                    $sq_key = (is_array($sq_value)) ? array_key_first($sq_value) : $sq_value;

                                                    if (!isset($grouped_months[$month][$sq_key])) {
                                                        $grouped_months[$month][$sq_key] = array(
                                                            "people" => 0
                                                            , "total" => 0
                                                        );
                                                    }
            
                                                    $q_value = $survey[$chart["record"]->question];
                                                    $q_key = (is_array($q_value)) ? array_key_first($q_value) : $q_value;
                                                    if (!isset($grouped_months[$month][$sq_key][$q_key])) {
                                                        $grouped_months[$month][$sq_key][$q_key] = 0;
                                                    }

                                                    $grouped_months[$month][$sq_key][$q_key] += $q_value[$q_key];
                                                    $grouped_months[$month][$sq_key]["people"] += $q_value[$q_key];
                                                    if (is_numeric($q_key)) {
                                                        $grouped_months[$month][$sq_key]["total"] += $q_key;
                                                    }
                                                    $people_total += 1;
                                                }
                                            }
                                        }
                                    }

                                    krsort($grouped_months);

                                    $grouped = array();
                                    $n = 0;
                                    foreach ($grouped_months as $month => $groups) {
                                        if (!$groups) {
                                            continue;
                                        }

                                        $n++;

                                        if (!isset($grouped[$month])) {
                                            $grouped[$month] = array();

                                            foreach ($chart["subquestion"]["grouped"] as $answer => $total) {
                                                $grouped[$month][$answer] = "null";
                                            }
                                        }

                                        foreach ($groups as $answer => $values) {
                                            if (($values["people"])) {
                                                $grouped[$month][$answer] = number_format($values["total"] / $values["people"], 1);
                                            }
                                        }

                                        if ($n == $take) {
                                            break;
                                        }
                                    }

                                    ksort($grouped);
                                    @endphp
                                    <div class="chart_box">
                                        <canvas class="charts" id="{{$chart_id}}"></canvas>
                                        <component :is="'script'">
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                        
                                                const {{$chart_id}} = new Chart(ctx, {
                                                    type: 'line'
                                                    , data: {
                                                        labels: [
                                                            @php
                                                            echo "'" . implode("', '", array_keys($grouped)) . "'";
                                                            @endphp
                                                        ]
                                                        , datasets: [
                                                            @php
                                                            //$colors = explode(",", $chart["record"]->list_color);
                                                            $colors = array(
                                                                0 => "#F05978"
                                                                , 1 => "#FFCD90"
                                                                , 2 => "#3FC67A"
                                                                , 3 => "#C7BBE1"
                                                                , 4 => "#FFBED4"
                                                            );

                                                            $i = -1;
                                                            foreach ($chart["subquestion"]["grouped"] as $answer => $total) {
                                                                $i++;

                                                                $color = $colors[$i];
                                                                //$color = (isset($colors[$i])) ? $colors[$i] : "#dddddd";
                                                                @endphp
                                                                {
                                                                    label: "{{$answer}}"
                                                                    , data: [
                                                                        @php
                                                                        $data = array();
                                                                        foreach ($grouped as $month => $answers) {
                                                                            $data[] = $answers[$answer];
                                                                        }
                                                                        echo implode(",", $data);
                                                                        @endphp
                                                                    ]
                                                                    , fill: false
                                                                    , borderColor: "{{$color}}"
                                                                    , backgroundColor: "{{$color}}"
                                                                    , spanGaps: true
                                                                },
                                                                @php
                                                            }
                                                            @endphp
                                                        ]
                                                    }
                                                    , options: {
                                                        scales: {
                                                            y: {
                                                                ticks: {
                                                                    autoSkip: false
                                                                    , stepSize: 0.5
                                                                }
                                                            }
                                                        }
                                                        , plugins: {
                                                            title: {
                                                                display: true
                                                                , text: [
                                                                    "TOTALE RISPONDENTI {{$people_total}}"
                                                                    @php
                                                                    if ($chart["record"]->question_type == "BT") {
                                                                        echo ', "NON RISPONDENTI ' . ($submissions - $people_grand_total) . '"';
                                                                    }
                                                                    @endphp
                                                                ]
                                                                , font: {
                                                                    size: 14
                                                                }
                                                            }
                                                            , legend: {
                                                                labels: {
                                                                    usePointStyle: true
                                                                }
                                                            }
                                                            , datalabels: false
                                                        }
                                                    }
                                                });
                                            });
                                        </component>
                                    </div>
                                    @php
                                    $chart_id = "chart_bars_" . $id;

                                    $grouped = array();
                                    $data = array();
                                    $n = 0;
                                    foreach ($grouped_months as $month => $groups) {
                                        if (!$groups) {
                                            continue;
                                        }

                                        $n++;

                                        foreach ($groups as $answer => $values) {
                                            if (!isset($grouped[$answer])) {
                                                $grouped[$answer] = array(
                                                    "people" => 0
                                                    , "total" => 0
                                                    , "average" => 0
                                                );
                                            }

                                            $grouped[$answer]["people"] += $values["people"];
                                            $grouped[$answer]["total"] += $values["total"];

                                            if (($grouped[$answer]["people"])) {
                                                $data[$answer] = number_format($grouped[$answer]["total"] / $grouped[$answer]["people"], 1);
                                            }
                                        }
                                    }

                                    $height = (count($grouped) + 1.5) * 15;
                                    @endphp
                                    <div class="chart_box">
                                        <canvas class="charts" height="{{$height}}" id="{{$chart_id}}"></canvas>
                                        <component :is="'script'">
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                        
                                                const {{$chart_id}} = new Chart(ctx, {
                                                    type: 'bar'
                                                    , data: {
                                                        labels: [
                                                            @php
                                                            echo "'" . implode("', '", array_keys($chart["subquestion"]["grouped"])) . "'";
                                                            @endphp
                                                        ]
                                                        , datasets: [{
                                                            label: 'Rispondenti'
                                                            , data: [
                                                                @php
                                                                echo implode(",", $data);
                                                                @endphp
                                                            ]
                                                            , backgroundColor: [
                                                                'rgba(255, 99, 132, 0.2)'
                                                            ]
                                                            , borderColor: [
                                                                'rgba(255, 99, 132, 1)'
                                                            ]
                                                            , borderWidth: 1
                                                        }]
                                                    }
                                                    , options: {
                                                        indexAxis: 'y'
                                                        , scales: {
                                                            y: {
                                                                type: "category"
                                                                , ticks: {
                                                                    autoSkip: false
                                                                }
                                                                , grid: {
                                                                    display: false
                                                                }
                                                            }
                                                            , x: {
                                                                display: false
                                                                , grid: {
                                                                    display: false
                                                                }
                                                            }
                                                        }
                                                        , plugins: {
                                                            legend: {
                                                                display: false
                                                            }
                                                            , title: {
                                                                display: false
                                                            }
                                                            , datalabels: {
                                                                formatter: (value, ctx) => {
                                                                    return value;
                                                                }
                                                                , anchor: "end"
                                                                , align: "left"
                                                                , clamp: true
                                                            }
                                                        }
                                                    }
                                                });
                                            });
                                        </component>
                                    </div>
                                    @php
                                    break;

                                case "bar2":
                                    $chart_id = "chart_" . $id;
                                    
                                    $keys = array();
                                    $grouped_months = array();
                                    $months_values = array();
                                    if (!$values["grouped"]) {
                                        foreach ($values["subquestions"] as $key => $tmp_values) {
                                            if (!$tmp_values) {
                                                continue;
                                            }

                                            foreach ($tmp_values["months"] as $key => $answers) {
                                                if (!array_key_exists($key, $months_values)) {
                                                    $months_values[$key] = array();
                                                }

                                                if (!array_key_exists($tmp_values["text"], $months_values[$key])) {
                                                    $months_values[$key][$tmp_values["text"]] = 0;
                                                    $keys[$tmp_values["text"]] = $tmp_values["text"];
                                                }

                                                $months_values[$key][$tmp_values["text"]] += $answers[array_key_first($answers)];
                                            }
                                        }

                                        $grouped_months = $months_values;
                                        $keys = array_keys($keys);

                                    } else {
                                        $grouped_months = $values["months"];
                                        $keys = array_keys($grouped);
                                    }

                                    krsort($grouped_months);

                                    $grouped = array();
                                    foreach ($keys as $key) {
                                        $grouped[$key] = 0;
                                    }

                                    $people_total = 0;
                                    $n = 0;
                                    foreach ($grouped_months as $key => $answers) {
                                        $n++;

                                        foreach ($answers as $answer => $people) {
                                            $grouped[$answer] += $people;
                                            $people_total += $people;
                                        }
                                    }

                                    $surveys_total = $people_total;
                                    if ($chart["record"]->question_type == "M") {
                                        $surveys_total = 0;
                                        foreach ($all_answers_grouped["answers_per_period"]["months"] as $month => $tmp_surveis) {
                                            foreach ($tmp_surveis as $tmp_survey) {
                                                $surveys_total++;
                                            }
                                        }
                                    }

                                    $tot_votes = 0;
                                    foreach ($grouped as $key => $value) {
                                        $tot_votes += $value * $key;
                                    }

                                    $average = number_format($tot_votes / $submissions, 1);

                                    $index_axis = "x";
                                    switch($chart["chart_type"]) {
                                        case "horizbar1":
                                            $index_axis = "y";
                                            break;
                                    }
                                    @endphp
                                    <div class="chart_box">
                                        <canvas class="charts" id="{{$chart_id}}"></canvas>
                                        <component :is="'script'">
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                        
                                                const {{$chart_id}} = new Chart(ctx, {
                                                    type: 'bar'
                                                    , data: {
                                                        labels: [
                                                            'Media'
                                                        ]
                                                        , datasets: [{
                                                            label: 'Rispondenti'
                                                            , data: [
                                                                @php   
                                                                echo "'" . $average . "'";
                                                                @endphp
                                                            ]
                                                            , backgroundColor: [
                                                                'rgba(255, 99, 132, 0.2)'
                                                            ]
                                                            , borderColor: [
                                                                'rgba(255, 99, 132, 1)'
                                                            ]
                                                            , borderWidth: 1
                                                        }]
                                                    }
                                                    , options: {
                                                        indexAxis: '<?=$index_axis; ?>'
                                                        , scales: {
                                                            '<?=$index_axis; ?>': {
                                                                type: "category"
                                                                , ticks: {
                                                                    autoSkip: false
                                                                }
                                                            }
                                                            , <?=($index_axis == "y") ? "x" : "y"; ?>: {
                                                                type: "linear"
                                                                , beginAtZero: true
                                                                , min: 0
                                                                , max: 10
                                                                , ticks: {
                                                                    stepSize: 1
                                                                }
                                                            }
                                                        }
                                                        , plugins: {
                                                            legend: {
                                                                display: false
                                                            }
                                                            , title: {
                                                                display: true
                                                                , text: [
                                                                    "TOTALE RISPONDENTI {{$people_total}}"
                                                                    @php
                                                                    if ($chart["record"]->question_type == "BB") {
                                                                        echo ', "NON RISPONDENTI ' . ($submissions - $people_total) . '"';
                                                                    }
                                                                    @endphp
                                                                ]
                                                                , font: {
                                                                    size: 14
                                                                }
                                                            }
                                                            , datalabels: {
                                                                formatter: (value, ctx) => {
                                                                    return value;
                                                                }
                                                                , anchor: "end"
                                                                , align: "<?=($index_axis == "y") ? "right" : "top"; ?>"
                                                                , clamp: true
                                                            }
                                                        }
                                                    }
                                                });
                                            });
                                        </component>
                                    </div>
                                    @php
                                    break;

                                case "pie1":
                                    $chart_id = "chart_" . $id;
                                    
                                    $keys = array();
                                    $grouped_months = array();
                                    $months_values = array();
                                    if (!$values["grouped"]) {
                                        foreach ($values["subquestions"] as $key => $tmp_values) {
                                            if (!$tmp_values) {
                                                continue;
                                            }

                                            foreach ($tmp_values["months"] as $key => $answers) {
                                                if (!array_key_exists($key, $months_values)) {
                                                    $months_values[$key] = array();
                                                }

                                                if (!array_key_exists($tmp_values["text"], $months_values[$key])) {
                                                    $months_values[$key][$tmp_values["text"]] = 0;
                                                    $keys[$tmp_values["text"]] = $tmp_values["text"];
                                                }

                                                $months_values[$key][$tmp_values["text"]] += $answers[array_key_first($answers)];
                                            }
                                        }

                                        $grouped_months = $months_values;
                                        $keys = array_keys($keys);

                                    } else {
                                        $grouped_months = $values["months"];
                                        $keys = array_keys($grouped);
                                    }

                                    krsort($grouped_months);

                                    $grouped = array();
                                    foreach ($keys as $key) {
                                        $grouped[$key] = 0;
                                    }

                                    $people_total = 0;
                                    $n = 0;
                                    foreach ($grouped_months as $key => $answers) {
                                        $n++;

                                        foreach ($answers as $answer => $people) {
                                            $grouped[$answer] += $people;
                                            $people_total += $people;
                                        }
                                    }

                                    $surveys_total = $people_total;
                                    if ($chart["record"]->question_type == "M") {
                                        $surveys_total = 0;
                                        foreach ($all_answers_grouped["answers_per_period"]["months"] as $month => $tmp_surveis) {
                                            foreach ($tmp_surveis as $tmp_survey) {
                                                $surveys_total++;
                                            }
                                        }
                                    }

                                    $tot_votes = 0;
                                    foreach ($grouped as $key => $value) {
                                        $tot_votes += $value * $key;
                                    }

                                    $average = number_format($tot_votes / $submissions * 10, 1);
                                    $rest = 100 - $average;
                                    @endphp
                                    <div class="chart_box">
                                        <canvas class="charts" style="max-height:500px;" id="{{$chart_id}}"></canvas>
                                        <component :is="'script'">
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                                                
                                                const {{$chart_id}} = new Chart(ctx, {
                                                    type: "doughnut"
                                                    , data: {
                                                        labels: [
                                                            "Rispondenti"
                                                            , "Non rispondenti"
                                                        ]
                                                        , datasets: [{
                                                            label: "Rispondenti"
                                                            , data: [
                                                                "{{$average}}"
                                                                , "{{$rest}}"
                                                            ]
                                                            , backgroundColor: [
                                                                "rgba(255, 99, 132, 1)"
                                                                , "rgba(54, 162, 235, 1)"
                                                            ]
                                                        }]
                                                    }
                                                    , options: {
                                                        plugins: {
                                                            title: {
                                                                display: true
                                                                , text: [
                                                                    "TOTALE RISPONDENTI {{$people_total}}"
                                                                    @php
                                                                    if ($chart["record"]->question_type == "BB") {
                                                                        echo ', "NON RISPONDENTI ' . ($submissions - $people_total) . '"';
                                                                    }
                                                                    @endphp
                                                                ]
                                                                , font: {
                                                                    size: 14
                                                                }
                                                            }
                                                            , datalabels: {
                                                                color: "#ffffff"
                                                                , formatter: (value, ctx) => {
                                                                    return value + " %";
                                                                }
                                                            }
                                                        }
                                                    }
                                                });
                                            });
                                        </component>
                                    </div>
                                    @php
                                    break;

                                case "mixed:1":
                                case "mixed:5":
                                    $chart_id = "chart_" . $id;
                                    $keys = array_keys($grouped);
                                    $grouped_months = $values["months"];
                                    krsort($grouped_months);

                                    $people_grand_total = 0;
                                    $no = "N";
                                    $yes = "Y";
                                    if (in_array($chart["record"]->answer, array("Sì", "No"))) {
                                        $no = "No";
                                        $yes = "Sì";
                                    }
                                    $votes_total = array(
                                        $no => 0
                                        , $yes => 0
                                        , "Non so / Non risponde" => 0
                                    );
                                  
                                    $n = 0;
                                    foreach ($grouped_months as $key => $answers) {
                                        $n++;

                                        //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                        $people_total = 0;
                                        foreach ($answers as $answer => $people) {
                                            $votes_total[$answer] += $people;
                                            if ($answer != "Non so / Non risponde") {
                                                $people_total += $people;
                                            }
                                        }
                                        $people_grand_total += $people_total;
                                    }

                                    $no_percent = 0;
                                    $yes_percent = 0;
                                    if ($people_grand_total) {
                                        $no_percent = number_format($votes_total[$no] * 100 / $people_grand_total, 1);
                                        $yes_percent = number_format($votes_total[$yes] * 100 / $people_grand_total, 1);
                                    }

                                    $data = array($no_percent, $yes_percent);
                                    if ($chart["record"]->answer_value == $yes) {
                                        $data = array($yes_percent, $no_percent);
                                        $keys = array_reverse($keys, true);
                                    }
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                                                        
                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: "doughnut"
                                                            , data: {
                                                                labels: [
                                                                    @php
                                                                    echo "'" . implode("', '", $keys) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: "Rispondenti"
                                                                    , data: [
                                                                        @php
                                                                        echo '"' . implode('", "', $data) . '"';
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        "rgba(255, 99, 132, 1)"
                                                                        , "rgba(54, 162, 235, 1)"
                                                                    ]
                                                                }]
                                                            }
                                                            , options: {
                                                                plugins: {
                                                                    title: {
                                                                        display: true
                                                                        , text: [
                                                                            "TOTALE RISPONDENTI {{$people_grand_total}}"
                                                                            @php
                                                                            echo ', "NON RISPONDENTI ' . ($submissions - $people_grand_total) . '"';
                                                                            @endphp
                                                                        ]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        color: "#ffffff"
                                                                        , formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                        </div>
                                        @php
                                        
                                        $chart_id = "chart_month_" . $id;
                                        $grouped_months = $values["months"];
                                        krsort($grouped_months);

                                        $people_per_step = array();
                                        $data = array();
                                        $n = 0;

                                        $showed_answer = $no;
                                        if ($chart["record"]->answer_value == $yes) {
                                            $showed_answer = $yes;
                                        }

                                        foreach ($grouped_months as $key => $answers) {
                                            $n++;

                                            //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                            $votes = array(
                                                $no => 0
                                                , $yes => 0
                                                , "Non so / Non risponde" => 0
                                            );

                                            $people_total = 0;
                                            foreach ($answers as $answer => $people) {
                                                $votes[$answer] += $people;
                                                if ($answer != "Non so / Non risponde") {
                                                    $people_total += $people;
                                                }
                                            }
                                            $people_per_step[$key] = $key . ";" . $votes[$showed_answer];

                                            $percentage = ($people_total) ? number_format($votes[$showed_answer] * 100 / $people_total, 1) : 0;
                                            
                                            $data[$key] = $percentage;

                                            if ($n == $take) {
                                                break;
                                            }
                                        }
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');

                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: 'bar'
                                                            , data: {
                                                                labels: [
                                                                    @php   
                                                                    echo "'" . implode("', '", $people_per_step) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: 'Media'
                                                                    , data: [
                                                                        @php   
                                                                        echo "'" . implode("', '", $data) . "'";
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        'rgba(255, 99, 132, 0.2)'
                                                                    ]
                                                                    , borderColor: [
                                                                        'rgba(255, 99, 132, 1)'
                                                                    ]
                                                                    , borderWidth: 1
                                                                }]
                                                            }
                                                            , options: {
                                                                indexAxis: "x"
                                                                , scales: {
                                                                    x: {
                                                                        reverse: true
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);
                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return step;
                                                                            }
                                                                        }
                                                                    }
                                                                    , xAxis2: {
                                                                        position: "top"
                                                                        , reverse: true
                                                                        , grid: {
                                                                            drawOnChartArea: false
                                                                        }
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);

                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return people;
                                                                            }
                                                                        }
                                                                    }
                                                                    , y: {
                                                                        type: "linear"
                                                                        , beginAtZero: true
                                                                        , min: 0
                                                                        , max: 100
                                                                        , ticks: {
                                                                            stepSize: 10
                                                                        }
                                                                    }
                                                                }
                                                                , plugins: {
                                                                    legend: {
                                                                        display: false
                                                                    }
                                                                    , title: {
                                                                        display: true
                                                                        , text: ["ULTIMI {{$take}} MESI"]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                            @php
                                            $chart_id = "chart_week_" . $id;
                                            $grouped_weeks = $values["weeks"];
                                            krsort($grouped_weeks);

                                            $showed_answer = $no;
                                            if ($chart["record"]->answer_value == $yes) {
                                                $showed_answer = $yes;
                                            }

                                            $people_per_step = array();
                                            $data = array();
                                            $n = 0;
                                            foreach ($grouped_weeks as $key => $answers) {
                                                $n++;

                                                //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                                $votes = array(
                                                    $no => 0
                                                    , $yes => 0
                                                    , "Non so / Non risponde" => 0
                                                );

                                                $people_total = 0;
                                                foreach ($answers as $answer => $people) {
                                                    $votes[$answer] += $people;
                                                    if ($answer != "Non so / Non risponde") {
                                                        $people_total += $people;
                                                    }
                                                }
                                                $people_per_step[$key] = $key . ";" . $votes[$showed_answer];

                                                $percentage = ($people_total) ? number_format($votes[$showed_answer] * 100 / $people_total, 1) : 0;
                                                
                                                $data[$key] = $percentage;

                                                if ($n == $take) {
                                                    break;
                                                }
                                            }
                                            @endphp
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');

                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: 'bar'
                                                            , data: {
                                                                labels: [
                                                                    @php   
                                                                    echo "'" . implode("', '", $people_per_step) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: 'Media'
                                                                    , data: [
                                                                        @php   
                                                                        echo "'" . implode("', '", $data) . "'";
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        'rgba(255, 99, 132, 0.2)'
                                                                    ]
                                                                    , borderColor: [
                                                                        'rgba(255, 99, 132, 1)'
                                                                    ]
                                                                    , borderWidth: 1
                                                                }]
                                                            }
                                                            , options: {
                                                                indexAxis: "x"
                                                                , scales: {
                                                                    x: {
                                                                        reverse: true
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);
                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return step;
                                                                            }
                                                                        }
                                                                    }
                                                                    , xAxis2: {
                                                                        position: "top"
                                                                        , reverse: true
                                                                        , grid: {
                                                                            drawOnChartArea: false
                                                                        }
                                                                        , ticks: {
                                                                            callback: function(label) {
                                                                                let realLabel = this.getLabelForValue(label);

                                                                                var step = realLabel.split(";")[0];
                                                                                var people = realLabel.split(";")[1];

                                                                                return people;
                                                                            }
                                                                        }
                                                                    }
                                                                    , y: {
                                                                        type: "linear"
                                                                        , beginAtZero: true
                                                                        , min: 0
                                                                        , max: 100
                                                                        , ticks: {
                                                                            stepSize: 10
                                                                        }
                                                                    }
                                                                }
                                                                , plugins: {
                                                                    legend: {
                                                                        display: false
                                                                    }
                                                                    , title: {
                                                                        display: true
                                                                        , text: ["ULTIME {{$take}} SETTIMANE"]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    break;

                                case "mixed:9":
                                    $chart_id = "chart_" . $id;
                                    $keys = array_keys($grouped);
                                    $grouped_months = $values["months"];
                                    krsort($grouped_months);

                                    $people_grand_total = 0;
                                    $no = "N";
                                    $yes = "Y";
                                    if (in_array($chart["record"]->answer, array("Sì", "No"))) {
                                        $no = "No";
                                        $yes = "Sì";
                                    }
                                    $votes_total = array(
                                        $no => 0
                                        , $yes => 0
                                        , "Non so / Non risponde" => 0
                                    );
                                    
                                    $n = 0;
                                    foreach ($grouped_months as $key => $answers) {
                                        $n++;

                                        //$answers = json_change_key($answers, "S\u00c3\u00ac", "Sì");

                                        $people_total = 0;
                                        foreach ($answers as $answer => $people) {
                                            $votes_total[$answer] += $people;
                                            if ($answer != "Non so / Non risponde") {
                                                $people_total += $people;
                                            }
                                        }
                                        $people_grand_total += $people_total;
                                    }

                                    $no_percent = 0;
                                    $yes_percent = 0;
                                    if ($people_grand_total) {
                                        $no_percent = number_format($votes_total[$no] * 100 / $people_grand_total, 1);
                                        $yes_percent = number_format($votes_total[$yes] * 100 / $people_grand_total, 1);
                                    }

                                    $data = array($no_percent, $yes_percent);
                                    if ($chart["record"]->answer_value == "Y") {
                                        $data = array($yes_percent, $no_percent);
                                        $keys = array_reverse($keys, true);
                                    }
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="chart_box">
                                                <canvas class="charts" id="{{$chart_id}}"></canvas>
                                                <component :is="'script'">
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const ctx = document.getElementById('{{$chart_id}}').getContext('2d');
                                                        
                                                        const {{$chart_id}} = new Chart(ctx, {
                                                            type: "doughnut"
                                                            , data: {
                                                                labels: [
                                                                    @php
                                                                    echo "'" . implode("', '", $keys) . "'";
                                                                    @endphp
                                                                ]
                                                                , datasets: [{
                                                                    label: "Rispondenti"
                                                                    , data: [
                                                                        @php
                                                                        echo '"' . implode('", "', $data) . '"';
                                                                        @endphp
                                                                    ]
                                                                    , backgroundColor: [
                                                                        "rgba(255, 99, 132, 1)"
                                                                        , "rgba(54, 162, 235, 1)"
                                                                    ]
                                                                }]
                                                            }
                                                            , options: {
                                                                plugins: {
                                                                    title: {
                                                                        display: true
                                                                        , text: [
                                                                            "TOTALE RISPONDENTI {{$people_grand_total}}"
                                                                        ]
                                                                        , font: {
                                                                            size: 14
                                                                        }
                                                                    }
                                                                    , datalabels: {
                                                                        color: "#ffffff"
                                                                        , formatter: (value, ctx) => {
                                                                            return value + " %";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    });
                                                </component>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    break;

                                default:
                                    ?>
                                    <div class="chart_box"></div>
                                    <?php
                                    break;
                            }
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
            @php
        }
        @endphp
    </div>
</div>
@endsection
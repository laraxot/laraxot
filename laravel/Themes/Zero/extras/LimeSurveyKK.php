<?php

declare(strict_types=1);
use Illuminate\Database\Connection;

// ponytail: questo file è un'integrazione legacy con LimeSurvey, refactoring richiederebbe rewriting completo

use function Safe\preg_replace;

class LimeSurveyKK
{
    public ?Connection $db_lime = null;

    public ?Connection $db_xot = null;

    public ?Connection $db_quaeris = null;

    public ?Connection $db = null;

    public int $survey_id = 0;

    public array $tables = [];

    public string $base_schema = '';

    public string $charts_schema = '';

    public function __construct(int $survey_id)
    {
        $this->survey_id = $survey_id;

        $this->db_lime = DB::connection('limesurvey');
        $this->db_quaeris = DB::connection('quaeris');
        $this->db_xot = DB::connection('mysql');

        $this->db = $this->db_lime;

        $tables = $this->db->select('SELECT * FROM INFORMATION_SCHEMA.TABLES');
        /** @var array<stdClass> $tables */
        foreach ($tables as $key => $table) {
            if (! isset($this->tables[$table->TABLE_SCHEMA])) {
                $this->tables[$table->TABLE_SCHEMA] = [];
            }
            $this->tables[$table->TABLE_SCHEMA][$table->TABLE_NAME] = $table->TABLE_SCHEMA.'.'.$table->TABLE_NAME;
        }

        $this->base_schema = 'txaesfry_quaeris_survey';
        $this->charts_schema = 'txaesfry_quaeris';
    }

    public function get_all_answers(array $params = [
        'survey_id' => null,
    ]): array
    {
        $sid = $params['survey_id'] ?? $this->survey_id;

        $db_questions = $this->db->select('
            SELECT
                q.*
                , ql.question AS text
            FROM
                '.$this->tables[$this->base_schema]['lime_questions'].' AS q
            LEFT JOIN
                '.$this->tables[$this->base_schema]['lime_question_l10ns']." AS ql
            ON
                ql.qid = q.qid
            WHERE
                q.sid = '".$sid."'
            AND
                q.type NOT IN ('X')
        ");

        $survey_fields = [];
        foreach ($db_questions as $db_question) {
            $qid = ($db_question->parent_qid) ?: $db_question->qid;
            $key = $db_question->sid.'X'.$db_question->gid.'X'.$qid;

            if (! isset($survey_fields[$key])) {
                $survey_fields[$key] = [
                    'qid' => $qid, 'sq' => [],
                ];
            }

            if (! $db_question->parent_qid) {
                $survey_fields[$key]['text'] = preg_replace("/[\s\n]+/i", ' ', strip_tags($db_question->text));
                $survey_fields[$key]['title'] = $db_question->title;
                $survey_fields[$key]['type'] = $db_question->type;
                $survey_fields[$key]['other'] = ($db_question->other == 'Y');
            } else {
                $sub_key = $key.$db_question->title;
                $survey_fields[$key]['sq'][$sub_key] = [
                    'text' => preg_replace("/[\s\n]+/i", ' ', strip_tags($db_question->text)), 'qid' => $db_question->qid, 'pqid' => $db_question->parent_qid, 'title' => $db_question->title, 'type' => $db_question->type,
                ];
            }
        }

        $sql = '
            SELECT
                ls.*
        ';

        if (isset($this->tables[$this->base_schema]['lime_tokens_'.$sid])) {
            $sql .= '
                , lt.firstname
                , lt.lastname
                , lt.email
                , lt.attribute_3
            ';
        }

        $sql .= '
            FROM
                '.$this->tables[$this->base_schema]['lime_survey_'.$sid].' AS ls
        ';

        if (isset($this->tables[$this->base_schema]['lime_tokens_'.$sid])) {
            $sql .= '
                LEFT JOIN
                    '.$this->tables[$this->base_schema]['lime_tokens_'.$sid].' AS lt
                ON
                    lt.token = ls.token
            ';
        }

        $surveys = $this->db->select($sql);

        $types_not_array = ['B', 'M', 'S', 'T'];
        $questions = [];
        $answer_types = [];
        foreach ($surveys as $survey) {
            $user = implode(', ', array_filter([$survey->firstname, $survey->lastname, $survey->email, $survey->attribute_3]));

            foreach ($survey_fields as $field => $attr) {
                if (property_exists($survey, $field)) {
                    if (! isset($questions[$attr['qid']])) {
                        $questions[$attr['qid']] = [
                            'text' => $attr['text'], 'answers' => [],
                        ];
                    }

                    $value = $survey->$field;

                    if ($attr['other'] && $value == '-oth-') {
                        $other_field = $field.'other';
                        $value = $survey->$other_field;
                    } elseif ($value) {
                        if (! in_array($attr['type'], $types_not_array)) {
                            if (! isset($answer_types[$attr['qid']][$value])) {
                                $answer = $this->db->selectOne('
                                    SELECT
                                        answer AS text
                                    FROM
                                        '.$this->tables[$this->base_schema]['lime_answer_l10ns']."
                                    WHERE
                                        aid = (
                                            SELECT
                                                aid
                                            FROM
                                                lime_answers
                                            WHERE
                                                qid = '".$attr['qid']."'
                                            AND
                                                code = '".$value."'
                                        )
                                ");

                                $answer_types[$attr['qid']][$value] = $answer;
                            }

                            $answer = $answer_types[$attr['qid']][$value];

                            if (! $answer) {
                                $dump = [
                                    'qid' => $attr['qid'], 'type' => $attr['type'], 'value' => $value,
                                ];

                                dddx($dump);
                                exit;
                            }

                            $value = $answer->text;
                        }
                    }

                    if ($value) {
                        $questions[$attr['qid']]['answers'][$user] = $value;
                    }
                }

                if ($attr['sq']) {
                    foreach ($attr['sq'] as $sub_field => $sub_attr) {
                        if (property_exists($survey, $sub_field)) {
                            if (! isset($questions[$sub_attr['qid']])) {
                                $questions[$sub_attr['qid']] = [
                                    'text' => $attr['text'].' > '.$sub_attr['text'], 'answers' => [],
                                ];
                            }

                            $value = $survey->$sub_field;

                            if ($value) {
                                if (! in_array($attr['type'], $types_not_array)) {
                                    if (! isset($answer_types[$sub_attr['qid']][$value])) {
                                        $answer = $this->db->selectOne('
                                            SELECT
                                                answer AS text
                                            FROM
                                                '.$this->tables[$this->base_schema]['lime_answer_l10ns']."
                                            WHERE
                                                aid = (
                                                    SELECT
                                                        aid
                                                    FROM
                                                        lime_answers
                                                    WHERE
                                                        qid = '".$sub_attr['pqid']."'
                                                    AND
                                                        code = '".$value."'
                                                )
                                        ");

                                        $answer_types[$sub_attr['qid']][$value] = $answer;
                                    }

                                    $answer = $answer_types[$sub_attr['qid']][$value];

                                    if (! $answer) {
                                        $dump = [
                                            'qid' => $sub_attr['qid'], 'pqid' => $sub_attr['pqid'], 'type' => $sub_attr['type'], 'ptype' => $attr['type'], 'value' => $value,
                                        ];

                                        dddx($dump);
                                        exit;
                                    }

                                    $value = $answer->text;
                                }

                                $questions[$sub_attr['qid']]['answers'][$user] = $value;
                            }
                        }
                    }
                }
            }
        }

        return $questions;
    }

    public function get_all_answers_grouped($params = [
        'survey_id' => null,
    ])
    {
        $sid = $params['survey_id'] ?? $this->survey_id;

        $db_questions = $this->db->select('
            SELECT
                q.*
                , ql.question AS text
            FROM
                '.$this->tables[$this->base_schema]['lime_questions'].' AS q
            LEFT JOIN
                '.$this->tables[$this->base_schema]['lime_question_l10ns']." AS ql
            ON
                ql.qid = q.qid
            WHERE
                q.sid = '".$sid."'
            AND
                q.type NOT IN ('X')
        ");

        $survey_fields = [];
        $questions_data = [];
        foreach ($db_questions as $db_question) {
            $qid = ($db_question->parent_qid) ?: $db_question->qid;
            $key = $db_question->sid.'X'.$db_question->gid.'X'.$qid;
            $questions_data[$qid] = $db_question;

            if (! isset($survey_fields[$key])) {
                $survey_fields[$key] = [
                    'qid' => $qid, 'sq' => [],
                ];
            }

            if (! $db_question->parent_qid) {
                $survey_fields[$key]['text'] = trim(preg_replace("/[\s\n]+/i", ' ', strip_tags($db_question->text)));
                $survey_fields[$key]['title'] = $db_question->title;
                $survey_fields[$key]['type'] = $db_question->type;
                $survey_fields[$key]['other'] = ($db_question->other == 'Y');
            } else {
                $sub_key = $key.$db_question->title;
                $survey_fields[$key]['sq'][$sub_key] = [
                    'text' => trim(preg_replace("/[\s\n]+/i", ' ', strip_tags($db_question->text))), 'qid' => $db_question->qid, 'pqid' => $db_question->parent_qid, 'title' => $db_question->title, 'type' => $db_question->type,
                ];
            }
        }

        $survey_pdf = $this->db_quaeris->selectOne("
            SELECT
                *
            FROM
                survey_pdfs AS sp
            WHERE
                sp.survey_id = '".$sid."'
        ");

        $survey_date_from = (isset($_GET['date_from'])) ? $_GET['date_from'] : (($survey_pdf->date_from) ?: '');
        $survey_date_to = (isset($_GET['date_to'])) ? $_GET['date_to'] : (($survey_pdf->date_to) ?: '');

        $survey_date_from = (! preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/i", $survey_date_from)) ? '0000-00-00' : $survey_date_from;
        $survey_date_to = (! preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/i", $survey_date_to)) ? '0000-00-00' : $survey_date_to;

        $survey_filter = (isset($_GET['filter'])) ? $_GET['filter'] : null;

        $sql = '
            SELECT
                ls.*
        ';

        if (isset($this->tables[$this->base_schema]['lime_tokens_'.$sid])) {
            $sql .= '
                , lt.firstname
                , lt.lastname
                , lt.email
                , lt.attribute_3
            ';
        }

        $sql .= '
                , YEAR(ls.submitdate) AS year
                , MONTH(ls.submitdate) AS month
                , WEEK(ls.submitdate) AS week
            FROM
                '.$this->tables[$this->base_schema]['lime_survey_'.$sid].' AS ls
        ';

        if (isset($this->tables[$this->base_schema]['lime_tokens_'.$sid])) {
            $sql .= '
                LEFT JOIN
                    '.$this->tables[$this->base_schema]['lime_tokens_'.$sid].' AS lt
                ON
                    lt.token = ls.token
            ';
        }

        $sql .= '
            WHERE
                ls.submitdate IS NOT NULL
        ';

        if ($survey_date_from != '0000-00-00') {
            $sql .= "
                AND
                    ls.submitdate >= '".$survey_date_from."'
            ";
        }

        if ($survey_date_to != '0000-00-00') {
            $sql .= "
                AND
                    ls.submitdate <= '".$survey_date_to."'
            ";
        }

        if ($survey_filter) {
            $filter_field = $sid.'X'.$questions_data[$qid]->gid.'X'.$survey_pdf->question_filter;

            $sql .= '
                AND
                    ls.'.$filter_field." = '".str_replace("'", "\'", $survey_filter)."'
            ";
        }

        $sql .= '
            ORDER BY
                ls.submitdate DESC
        ';

        $surveys = $this->db->select($sql);

        $types_not_array = ['B', 'M', 'S', 'T'];
        $questions = [];
        $answers_per_period = [
            'months' => [], 'weeks' => [],
        ];
        $answers_per_record = [];
        $answer_types = [];
        foreach ($surveys as $survey) {
            // $user = implode(", ", array_filter(array($survey->firstname, $survey->lastname, $survey->email, $survey->attribute_3)));
            $month = sprintf('%02d', $survey->month);
            $week = sprintf('%02d', $survey->week);

            if (! isset($answers_per_period['months'][$survey->year.'-'.$month])) {
                $answers_per_period['months'][$survey->year.'-'.$month] = [];
            }

            if (! isset($answers_per_period['months'][$survey->year.'-'.$month][$survey->id])) {
                $answers_per_period['months'][$survey->year.'-'.$month][$survey->id] = [];
            }

            if (! isset($answers_per_period['weeks'][$survey->year.'-'.$week])) {
                $answers_per_period['weeks'][$survey->year.'-'.$week] = [];
            }

            if (! isset($answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id])) {
                $answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id] = [];
            }

            if (! isset($answers_per_record[$survey->id])) {
                $answers_per_record[$survey->id] = [];
            }

            foreach ($survey_fields as $field => $attr) {
                if (! isset($questions[$attr['qid']])) {
                    $questions[$attr['qid']] = [
                        'text' => trim($attr['text']), 'subquestions' => [], 'grouped' => [], 'months' => [], 'weeks' => [], 'year' => [], 'tot' => 0,
                    ];
                }

                if (property_exists($survey, $field)) {
                    $value = $survey->$field;

                    if ($attr['other'] && $value == '-oth-') {
                        $other_field = $field.'other';
                        $value = $survey->$other_field;
                    } elseif ($value) {
                        if (! in_array($attr['type'], $types_not_array)) {
                            if (! isset($answer_types[$attr['qid']][$value])) {
                                $answer = $this->db->selectOne('
                                    SELECT
                                        answer AS text
                                    FROM
                                        '.$this->tables[$this->base_schema]['lime_answer_l10ns'].'
                                    WHERE
                                        aid = (
                                            SELECT
                                                aid
                                            FROM
                                                '.$this->tables[$this->base_schema]['lime_answers']."
                                            WHERE
                                                qid = '".$attr['qid']."'
                                            AND
                                                code = '".$value."'
                                        )
                                ");

                                $answer_types[$attr['qid']][$value] = $answer;
                            }

                            $answer = $answer_types[$attr['qid']][$value];

                            if (! $answer) {
                                $answer_types[$attr['qid']][$value] = $value;
                                // $dump = array(
                                //     "qid" => $attr["qid"]
                                //     , "type" => $attr["type"]
                                //     , "value" => $value
                                // );

                                // dddx($dump);
                                // exit;
                            } elseif (! is_object($answer)) {
                                $value = $answer;
                            } else {
                                $value = trim($answer->text);
                            }
                        }
                    }

                    if ($value) {
                        $value = addslashes($value);

                        // Grouped by answer
                        if (! isset($questions[$attr['qid']]['grouped'][$value])) {
                            $questions[$attr['qid']]['grouped'][$value] = 0;
                        }
                        $questions[$attr['qid']]['grouped'][$value]++;

                        // Grouped months, weeks
                        if (! isset($questions[$attr['qid']]['months'][$survey->year.'-'.$month])) {
                            $questions[$attr['qid']]['months'][$survey->year.'-'.$month] = [];
                        }

                        if (! isset($questions[$attr['qid']]['weeks'][$survey->year.'-'.$week])) {
                            $questions[$attr['qid']]['weeks'][$survey->year.'-'.$week] = [];
                        }

                        if (! isset($questions[$attr['qid']]['months'][$survey->year.'-'.$month][$value])) {
                            $questions[$attr['qid']]['months'][$survey->year.'-'.$month][$value] = 0;
                        }
                        $questions[$attr['qid']]['months'][$survey->year.'-'.$month][$value]++;

                        if (! isset($questions[$attr['qid']]['weeks'][$survey->year.'-'.$week][$value])) {
                            $questions[$attr['qid']]['weeks'][$survey->year.'-'.$week][$value] = 0;
                        }
                        $questions[$attr['qid']]['weeks'][$survey->year.'-'.$week][$value]++;

                        // Grouped per survey > months, weeks
                        if (! isset($answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$attr['qid']])) {
                            $answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$attr['qid']] = [];
                        }
                        if (! isset($answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$attr['qid']][$value])) {
                            $answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$attr['qid']][$value] = 0;
                        }
                        $answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$attr['qid']][$value]++;

                        if (! isset($answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$attr['qid']])) {
                            $answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$attr['qid']] = [];
                        }
                        if (! isset($answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$attr['qid']][$value])) {
                            $answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$attr['qid']][$value] = 0;
                        }
                        $answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$attr['qid']][$value]++;

                        // Grouped per record
                        if (! isset($answers_per_record[$survey->id][$attr['qid']])) {
                            $answers_per_record[$survey->id][$attr['qid']] = [];
                        }
                        if (! isset($answers_per_record[$survey->id][$attr['qid']][$value])) {
                            $answers_per_record[$survey->id][$attr['qid']][$value] = 0;
                        }
                        $answers_per_record[$survey->id][$attr['qid']][$value]++;

                        // Detailed year, month, week
                        // if (!isset($questions[$attr["qid"]]["year"][$survey->year])) {
                        //     $questions[$attr["qid"]]["year"][$survey->year] = array(
                        //         "week" => array()
                        //         , "month" => array()
                        //     );
                        // }

                        // if (!isset($questions[$attr["qid"]]["year"][$survey->year]["month"][$month])) {
                        //     $questions[$attr["qid"]]["year"][$survey->year]["month"][$month] = array();
                        // }

                        // if (!isset($questions[$attr["qid"]]["year"][$survey->year]["week"][$week])) {
                        //     $questions[$attr["qid"]]["year"][$survey->year]["week"][$week] = array();
                        // }

                        // if (!isset($questions[$attr["qid"]]["year"][$survey->year]["week"][$week][$value])) {
                        //     $questions[$attr["qid"]]["year"][$survey->year]["week"][$week][$value] = 0;
                        // }
                        // $questions[$attr["qid"]]["year"][$survey->year]["week"][$week][$value] += 1;

                        // if (!isset($questions[$attr["qid"]]["year"][$survey->year]["month"][$month][$value])) {
                        //     $questions[$attr["qid"]]["year"][$survey->year]["month"][$month][$value] = 0;
                        // }
                        // $questions[$attr["qid"]]["year"][$survey->year]["month"][$month][$value] += 1;

                        // Total
                        if (! isset($questions[$attr['qid']]['tot'])) {
                            $questions[$attr['qid']]['tot'] = 0;
                        }
                        $questions[$attr['qid']]['tot']++;
                    }
                }

                if ($attr['sq']) {
                    foreach ($attr['sq'] as $sub_field => $sub_attr) {
                        if (property_exists($survey, $sub_field)) {
                            if (! isset($questions[$sub_attr['qid']])) {
                                $questions[$sub_attr['qid']] = [
                                    'text' => ($sub_attr['text']) ? $attr['text'].' > '.$sub_attr['text'] : $attr['text'],
                                ];
                            }

                            if (! isset($questions[$attr['qid']]['subquestions'][$sub_attr['qid']])) {
                                $questions[$attr['qid']]['subquestions'][$sub_attr['qid']] = [];
                            }

                            $value = $survey->$sub_field;

                            if ($value) {
                                if (! in_array($attr['type'], $types_not_array)) {
                                    if (! isset($answer_types[$sub_attr['qid']][$value])) {
                                        $answer = $this->db->selectOne('
                                            SELECT
                                                answer AS text
                                            FROM
                                                '.$this->tables[$this->base_schema]['lime_answer_l10ns'].'
                                            WHERE
                                                aid = (
                                                    SELECT
                                                        aid
                                                    FROM
                                                        '.$this->tables[$this->base_schema]['lime_answers']."
                                                    WHERE
                                                        qid = '".$sub_attr['pqid']."'
                                                    AND
                                                        code = '".$value."'
                                                )
                                        ");

                                        $answer_types[$sub_attr['qid']][$value] = $answer;
                                    }

                                    $answer = $answer_types[$sub_attr['qid']][$value];

                                    if (! $answer) {
                                        $dump = [
                                            'qid' => $sub_attr['qid'], 'pqid' => $sub_attr['pqid'], 'type' => $sub_attr['type'], 'ptype' => $attr['type'], 'value' => $value,
                                        ];

                                        dddx($dump);
                                        exit;
                                    }

                                    $value = trim($answer->text);
                                }

                                $value = addslashes($value);

                                // Grouped by answer
                                if (! isset($questions[$sub_attr['qid']]['grouped'][$value])) {
                                    $questions[$sub_attr['qid']]['grouped'][$value] = 0;
                                }
                                $questions[$sub_attr['qid']]['grouped'][$value]++;

                                // Grouped months, weeks
                                if (! isset($questions[$sub_attr['qid']]['months'][$survey->year.'-'.$month])) {
                                    $questions[$sub_attr['qid']]['months'][$survey->year.'-'.$month] = [];
                                }

                                if (! isset($questions[$sub_attr['qid']]['weeks'][$survey->year.'-'.$week])) {
                                    $questions[$sub_attr['qid']]['weeks'][$survey->year.'-'.$week] = [];
                                }

                                if (! isset($questions[$sub_attr['qid']]['months'][$survey->year.'-'.$month][$value])) {
                                    $questions[$sub_attr['qid']]['months'][$survey->year.'-'.$month][$value] = 0;
                                }
                                $questions[$sub_attr['qid']]['months'][$survey->year.'-'.$month][$value]++;

                                if (! isset($questions[$sub_attr['qid']]['weeks'][$survey->year.'-'.$week][$value])) {
                                    $questions[$sub_attr['qid']]['weeks'][$survey->year.'-'.$week][$value] = 0;
                                }
                                $questions[$sub_attr['qid']]['weeks'][$survey->year.'-'.$week][$value]++;

                                // Grouped per survey > months, weeks
                                if (! isset($answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$sub_attr['qid']])) {
                                    $answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$sub_attr['qid']] = [];
                                }
                                if (! isset($answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$sub_attr['qid']][$value])) {
                                    $answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$sub_attr['qid']][$value] = 0;
                                }
                                $answers_per_period['months'][$survey->year.'-'.$month][$survey->id][$sub_attr['qid']][$value]++;

                                if (! isset($answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$sub_attr['qid']])) {
                                    $answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$sub_attr['qid']] = [];
                                }
                                if (! isset($answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$sub_attr['qid']][$value])) {
                                    $answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$sub_attr['qid']][$value] = 0;
                                }
                                $answers_per_period['weeks'][$survey->year.'-'.$week][$survey->id][$sub_attr['qid']][$value]++;

                                // Grouped per record
                                if (! isset($answers_per_record[$survey->id][$sub_attr['qid']])) {
                                    $answers_per_record[$survey->id][$sub_attr['qid']] = [];
                                }
                                if (! isset($answers_per_record[$survey->id][$sub_attr['qid']][$value])) {
                                    $answers_per_record[$survey->id][$sub_attr['qid']][$value] = 0;
                                }
                                $answers_per_record[$survey->id][$sub_attr['qid']][$value]++;

                                // Detailed year, month, week
                                // if (!isset($questions[$sub_attr["qid"]]["year"][$survey->year])) {
                                //     $questions[$sub_attr["qid"]]["year"][$survey->year] = array(
                                //         "week" => array()
                                //         , "month" => array()
                                //     );
                                // }

                                // if (!isset($questions[$sub_attr["qid"]]["year"][$survey->year]["month"][$month])) {
                                //     $questions[$sub_attr["qid"]]["year"][$survey->year]["month"][$month] = array();
                                // }

                                // if (!isset($questions[$sub_attr["qid"]]["year"][$survey->year]["week"][$week])) {
                                //     $questions[$sub_attr["qid"]]["year"][$survey->year]["week"][$week] = array();
                                // }

                                // if (!isset($questions[$sub_attr["qid"]]["year"][$survey->year])) {
                                //     $questions[$sub_attr["qid"]]["year"][$survey->year] = array(
                                //         "week" => array()
                                //         , "month" => array()
                                //     );
                                // }

                                // if (!isset($questions[$sub_attr["qid"]]["year"][$survey->year]["week"][$week][$value])) {
                                //     $questions[$sub_attr["qid"]]["year"][$survey->year]["week"][$week][$value] = 0;
                                // }
                                // $questions[$sub_attr["qid"]]["year"][$survey->year]["week"][$week][$value] += 1;

                                // if (!isset($questions[$sub_attr["qid"]]["year"][$survey->year]["month"][$month][$value])) {
                                //     $questions[$sub_attr["qid"]]["year"][$survey->year]["month"][$month][$value] = 0;
                                // }
                                // $questions[$sub_attr["qid"]]["year"][$survey->year]["month"][$month][$value] += 1;

                                // Total
                                if (! isset($questions[$sub_attr['qid']]['tot'])) {
                                    $questions[$sub_attr['qid']]['tot'] = 0;
                                }
                                $questions[$sub_attr['qid']]['tot']++;

                                $questions[$attr['qid']]['subquestions'][$sub_attr['qid']] = $questions[$sub_attr['qid']];
                                $questions[$attr['qid']]['subquestions'][$sub_attr['qid']]['text'] = $sub_attr['text'];
                            }
                        }
                    }
                }
            }
        }

        $return = [
            'questions' => $questions, 'charts' => [], 'answers_per_period' => $answers_per_period, 'answers_per_record' => $answers_per_record, 'totals' => [
                'sent' => [
                    'sms' => 0, 'email' => 0, 'all' => 0,
                ], 'answers' => [
                    'sms' => 0, 'email' => 0, 'all' => 0,
                ],
            ],
        ];

        $db_charts = $this->db->select('
            SELECT
                qc.id
                , qc.question
                , qc.subquestion
                , qc.take
                , qc.answer_value
                , qc.question_type
                , qc.chart_type AS question_chart_type
                , c.type AS chart_type
                , c.list_color
                , lal.answer AS answer
            FROM
                '.$this->tables[$this->charts_schema]['question_charts'].' AS qc

            LEFT JOIN
                '.$this->tables[$this->charts_schema]['charts']." AS c
            ON
                c.post_id = qc.id
            AND
                c.post_type = 'question_chart'

            LEFT JOIN
                ".$this->tables[$this->base_schema]['lime_answers'].' AS la
            ON
                la.qid = qc.question
            AND
                la.code = qc.answer_value

            LEFT JOIN
                '.$this->tables[$this->base_schema]['lime_answer_l10ns']." AS lal
            ON
                lal.aid = la.aid

            WHERE
                qc.survey_pdf_id = '".$survey_pdf->id."'
            ORDER BY
                qc.pos
        ");

        $charts = [];
        foreach ($db_charts as $db_chart) {
            $charts[$db_chart->id] = [
                'record' => $db_chart, 'answer_value' => $db_chart->answer_value, 'question_filter' => $survey_pdf->question_filter, 'question_chart_type' => $db_chart->question_chart_type, 'chart_type' => $db_chart->chart_type, 'question' => (array_key_exists($db_chart->question, $questions)) ? $questions[$db_chart->question] : $db_chart->question, 'subquestion' => (array_key_exists($db_chart->subquestion, $questions)) ? $questions[$db_chart->subquestion] : $db_chart->subquestion,
            ];
        }
        $return['charts'] = $charts;

        $tot_sms = $this->db->selectOne('
            SELECT
                count(id) AS total
            FROM
                '.$this->tables[$this->charts_schema]['contacts']."
            WHERE
                `survey_pdf_id` = '".$survey_pdf->id."'
            AND
                `sms_count` > 0
        ");

        $tot_email = null;
        if (isset($this->tables[$this->base_schema]['lime_tokens_'.$sid])) {
            $tot_email = $this->db->selectOne('
                SELECT
                    count(tid) AS total
                FROM
                    '.$this->tables[$this->base_schema]['lime_tokens_'.$sid]."
                WHERE
                    `sent` != 'N'
            ");
        }

        $return['totals']['sent']['sms'] = $tot_sms->total;
        $return['totals']['sent']['email'] = ($tot_email) ? $tot_email->total : 0;
        $return['totals']['sent']['all'] = $tot_sms->total + (($tot_email) ? $tot_email->total : 0);

        $tot_sms = $this->db->selectOne('
            SELECT
                count(`ls`.`id`) AS total
            FROM
                '.$this->tables[$this->base_schema]['lime_survey_'.$sid].' AS `ls`
            LEFT JOIN
                '.$this->tables[$this->charts_schema]['contacts'].' AS `B`
            ON
                `ls`.`token` = `B`.`token`
            WHERE
                `submitdate` IS NOT NULL
            AND
                `B`.`token` IS NOT NULL
            AND
                `B`.`sms_count` != 0
        ');

        $tot_email = null;
        if (isset($this->tables[$this->base_schema]['lime_tokens_'.$sid])) {
            $tot_email = $this->db->selectOne('
                SELECT
                    count(ls.id) AS total
                FROM
                    '.$this->tables[$this->base_schema]['lime_survey_'.$sid].' AS ls
                LEFT JOIN
                    '.$this->tables[$this->base_schema]['lime_tokens_'.$sid]." AS `B`
                ON
                    `ls`.`token` = `B`.`token`
                WHERE
                    `submitdate` IS NOT NULL
                AND
                    `B`.`sent` != 'N'
            ");
        }

        $return['totals']['answers']['sms'] = $tot_sms->total;
        $return['totals']['answers']['email'] = ($tot_email) ? $tot_email->total : 0;
        $return['totals']['answers']['all'] = $tot_sms->total + (($tot_email) ? $tot_email->total : 0);

        return $return;
    }

    public function get_questions($params = [
        'survey_id' => null,
    ])
    {
        $sid = $params['survey_id'] ?? $this->survey_id;

        $db_questions = $this->db->select("
            SELECT
                q.*
                , ql.question AS text
            FROM
                lime_questions AS q
            LEFT JOIN
                lime_question_l10ns AS ql
            ON
                ql.qid = q.qid
            WHERE
                q.sid = '".$sid."'
            AND
                q.type NOT IN ('X')
        ");

        $survey_fields = [];
        foreach ($db_questions as $db_question) {
            $qid = ($db_question->parent_qid) ?: $db_question->qid;
            $key = $db_question->sid.'X'.$db_question->gid.'X'.$qid;

            if (! isset($survey_fields[$key])) {
                $survey_fields[$key] = [
                    'qid' => $qid, 'sq' => [],
                ];
            }

            if (! $db_question->parent_qid) {
                $survey_fields[$key]['text'] = preg_replace("/[\s\n]+/i", ' ', strip_tags($db_question->text));
                $survey_fields[$key]['title'] = $db_question->title;
                $survey_fields[$key]['type'] = $db_question->type;
                $survey_fields[$key]['other'] = ($db_question->other == 'Y');
            } else {
                $sub_key = $key.$db_question->title;
                $survey_fields[$key]['sq'][$sub_key] = [
                    'text' => preg_replace("/[\s\n]+/i", ' ', strip_tags($db_question->text)), 'qid' => $db_question->qid, 'pqid' => $db_question->parent_qid, 'title' => $db_question->title, 'type' => $db_question->type,
                ];
            }
        }

        $surveys = $this->db->select('
            SELECT
                ls.*
                , lt.firstname
                , lt.lastname
                , lt.email
                , lt.attribute_3
            FROM
                lime_survey_'.$sid.' AS ls
            LEFT JOIN
                lime_tokens_'.$sid.' AS lt
            ON
                lt.token = ls.token
            WHERE
                ls.submitdate >= DATE_SUB(curdate(), INTERVAL 4 MONTH)
        ');

        $questions = [];
        foreach ($surveys as $survey) {
            foreach ($survey_fields as $field => $attr) {
                if (property_exists($survey, $field)) {
                    if (! isset($questions[$attr['qid']])) {
                        $questions[$attr['qid']] = [
                            'text' => $attr['text'],
                        ];
                    }
                }

                if ($attr['sq']) {
                    foreach ($attr['sq'] as $sub_field => $sub_attr) {
                        if (property_exists($survey, $sub_field)) {
                            if (! isset($questions[$sub_attr['qid']])) {
                                $questions[$sub_attr['qid']] = [
                                    'text' => $attr['text'].' > '.$sub_attr['text'],
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $questions;
    }

    public function get_question_by_id($params = [
        'id' => null, 'survey_id' => null,
    ])
    {
        $id = $params['id'] ?? 0;
        $sid = $params['survey_id'] ?? $this->survey_id;

        $surveys = $this->db->select('
            SELECT
                ls.*
                , lt.firstname
                , lt.lastname
                , lt.email
                , lt.attribute_3
            FROM
                lime_survey_'.$sid.' AS ls
            LEFT JOIN
                lime_tokens_'.$sid.' AS lt
            ON
                lt.token = ls.token
            WHERE
                ls.submitdate >= DATE_SUB(curdate(), INTERVAL 4 MONTH)
        ');

        $survey_fields = $this->get_survey_fields_by_question_id(['id' => $id]);

        $questions = [];
        foreach ($surveys as $survey) {
            foreach ($survey_fields as $field => $attr) {
                if (property_exists($survey, $field)) {
                    if (! isset($questions[$attr['qid']])) {
                        $questions[$attr['qid']] = [
                            'text' => $attr['text'],
                        ];
                    }
                }

                if ($attr['sq']) {
                    foreach ($attr['sq'] as $sub_field => $sub_attr) {
                        if (property_exists($survey, $sub_field)) {
                            if (! isset($questions[$sub_attr['qid']])) {
                                $questions[$sub_attr['qid']] = [
                                    'text' => $sub_attr['text'],
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $questions;
    }

    public function get_survey_fields_by_question_id($params = [
        'id' => null, 'survey_id' => null,
    ])
    {
        $id = $params['id'] ?? 0;
        $sid = $params['survey_id'] ?? $this->survey_id;

        $db_questions = $this->db->select("
            SELECT
                q.*
                , ql.question AS text
            FROM
                lime_questions AS q
            LEFT JOIN
                lime_question_l10ns AS ql
            ON
                ql.qid = q.qid
            WHERE
                q.sid = '".$sid."'
            AND
                q.qid = '".$id."'
        ");

        $survey_fields = [];
        foreach ($db_questions as $db_question) {
            $qid = ($db_question->parent_qid) ?: $db_question->qid;
            $key = $db_question->sid.'X'.$db_question->gid.'X'.$qid;

            if (! isset($survey_fields[$key])) {
                $survey_fields[$key] = [
                    'qid' => $qid, 'sq' => [], 'text' => '', 'title' => '', 'type' => '', 'other' => '',
                ];
            }

            if (! $db_question->parent_qid) {
                $survey_fields[$key]['text'] = preg_replace("/[\s\n]+/i", ' ', strip_tags($db_question->text));
                $survey_fields[$key]['title'] = $db_question->title;
                $survey_fields[$key]['type'] = $db_question->type;
                $survey_fields[$key]['other'] = ($db_question->other == 'Y');
            } else {
                $sub_key = $key.$db_question->title;
                $survey_fields[$key]['sq'][$sub_key] = [
                    'text' => preg_replace("/[\s\n]+/i", ' ', strip_tags($db_question->text)), 'qid' => $db_question->qid, 'pqid' => $db_question->parent_qid, 'title' => $db_question->title, 'type' => $db_question->type,
                ];
            }
        }

        return $survey_fields;
    }

    public function get_answers_by_question_id($params = [
        'id' => null, 'question_id' => null,
    ])
    {
        $id = $params['id'] ?? 0;
        $sid = $params['survey_id'] ?? $this->survey_id;

        $survey_fields = $this->get_survey_fields_by_question_id(['id' => $id]);

        $surveys = $this->db->select('
            SELECT
                ls.*
                , lt.firstname
                , lt.lastname
                , lt.email
                , lt.attribute_3
            FROM
                lime_survey_'.$sid.' AS ls
            LEFT JOIN
                lime_tokens_'.$sid.' AS lt
            ON
                lt.token = ls.token
        ');
        //         ls.submitdate >= DATE_SUB(curdate(), INTERVAL 4 MONTH)
        // ");

        $types_not_array = ['B', 'M', 'S', 'T'];
        $answers = [];
        $answer_types = [];
        foreach ($surveys as $survey) {
            $user = implode(', ', array_filter([$survey->firstname, $survey->lastname, $survey->email, $survey->attribute_3]));

            foreach ($survey_fields as $field => $attr) {
                if (property_exists($survey, $field)) {
                    if (! isset($answers[$attr['qid']])) {
                        $answers[$attr['qid']] = [
                            'text' => $attr['text'], 'answers' => [], 'users' => [], 'grouped' => [],
                        ];
                    }

                    $value = $survey->$field;

                    if ($attr['other'] && $value == '-oth-') {
                        $other_field = $field.'other';
                        $value = $survey->$other_field;
                    } elseif ($value) {
                        if (! in_array($attr['type'], $types_not_array)) {
                            if (! isset($answer_types[$attr['qid']][$value])) {
                                $answer = $this->db->selectOne("
                                    SELECT
                                        answer AS text
                                    FROM
                                        lime_answer_l10ns
                                    WHERE
                                        aid = (
                                            SELECT
                                                aid
                                            FROM
                                                lime_answers
                                            WHERE
                                                qid = '".$attr['qid']."'
                                            AND
                                                code = '".$value."'
                                        )
                                ");

                                $answer_types[$attr['qid']][$value] = $answer;
                            }

                            $answer = $answer_types[$attr['qid']][$value];

                            if (! $answer) {
                                $dump = [
                                    'qid' => $attr['qid'], 'type' => $attr['type'], 'value' => $value,
                                ];

                                dddx($dump);
                                exit;
                            }

                            $value = $answer->text;
                        }
                    }

                    if ($value) {
                        $answers[$id]['answers'][] = $value;
                        $answers[$id]['users'][$user] = $user;
                        if (! isset($answers[$id]['grouped'][$value])) {
                            $answers[$id]['grouped'][$value] = 0;
                        }
                        $answers[$id]['grouped'][$value]++;
                    }
                }

                if ($attr['sq']) {
                    foreach ($attr['sq'] as $sub_field => $sub_attr) {
                        if (property_exists($survey, $sub_field)) {
                            if (! isset($answers[$sub_attr['qid']])) {
                                $answers[$sub_attr['qid']] = [
                                    'text' => $attr['text'].' > '.$sub_attr['text'], 'answers' => [],
                                ];
                            }

                            $value = $survey->$sub_field;

                            if ($value) {
                                if (! in_array($attr['type'], $types_not_array)) {
                                    if (! isset($answer_types[$sub_attr['qid']][$value])) {
                                        $answer = $this->db->selectOne("
                                            SELECT
                                                answer AS text
                                            FROM
                                                lime_answer_l10ns
                                            WHERE
                                                aid = (
                                                    SELECT
                                                        aid
                                                    FROM
                                                        lime_answers
                                                    WHERE
                                                        qid = '".$sub_attr['pqid']."'
                                                    AND
                                                        code = '".$value."'
                                                )
                                        ");

                                        $answer_types[$sub_attr['qid']][$value] = $answer;
                                    }

                                    $answer = $answer_types[$sub_attr['qid']][$value];

                                    if (! $answer) {
                                        $dump = [
                                            'qid' => $sub_attr['qid'], 'pqid' => $sub_attr['pqid'], 'type' => $sub_attr['type'], 'ptype' => $attr['type'], 'value' => $value,
                                        ];

                                        dddx($dump);
                                        exit;
                                    }

                                    $value = $answer->text;
                                }

                                $answers[$id]['answers'][] = $value;
                                $answers[$id]['users'][$user] = $user;
                                if (! isset($answers[$id]['grouped'][$value])) {
                                    $answers[$id]['grouped'][$value] = 0;
                                }
                                $answers[$id]['grouped'][$value]++;
                            }
                        }
                    }
                }
            }
        }

        return $answers;
    }
}

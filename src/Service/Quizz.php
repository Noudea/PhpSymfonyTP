<?php


namespace App\Service;

class Quizz {
    private $questions = [
        [
            'question' => 'ceci est la question 1',
            'reponse' => 'ceci est la reponse 1'
        ],
        [
            'question' => 'ceci est la question 2',
            'reponse' => 'ceci est la reponse 2'
        ]
    ];

    public function getAllQuestions()
    {
        return $this->questions;
    }

    public function getRandomQuestion()
    {
        $max = count($this->questions)-1;
        $rand = rand(0,$max);
        $test = $this->questions[$rand]['question'];
        $reponse = $this->questions[$rand]['reponse'];

        $return = [
            'question'=>$test,
            'reponse' =>$reponse
        ];

        return $return;
    }
}
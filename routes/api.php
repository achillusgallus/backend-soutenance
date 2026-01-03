<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SujetsForumController;
use App\Http\Controllers\MessagesForumController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\ResultatsQuizController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AdminMatiereController;
use App\Http\Controllers\AdminForumController;
use App\Http\Controllers\ProfesseurMatiereController;
use App\Http\Controllers\ProfesseurCoursController;
use App\Http\Controllers\ProfesseurQuizController;
use App\Http\Controllers\professeurForumController;
use App\Http\Controllers\ProfesseurQuestionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\StudentMatiereController;


Route::post('/login', [AuthController::class, 'login']);   //V
Route::post('/register', [AuthController::class, 'register']);//V

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', fn (Request $request) => $request->user());//V

    Route::get('/cours', [CoursController::class, 'index']);  // liste des cours
    Route::get('/cours/{id}', [CoursController::class, 'show']); // détail cours
    Route::get('/student/matieres', [StudentMatiereController::class, 'index']); // matieres by classe

    Route::get('/quiz', [QuizController::class, 'index']);        // liste des quiz pour l'élève
    Route::get('/quiz/{id}', [QuizController::class, 'show']);   // détails d'un quiz

    Route::get('/quiz/{quiz_id}/questions', [QuestionController::class, 'index']);

    Route::get('/resultats', [ResultatsQuizController::class, 'index']);
    Route::post('/resultats', [ResultatsQuizController::class, 'store']);

    Route::get('/forums', [ForumController::class, 'index']);
    Route::get('/forums/{id}', [ForumController::class, 'show']);

    Route::get('/forums/{forum_id}/sujets', [SujetsForumController::class, 'index']);
    Route::post('/forums/sujets', [SujetsForumController::class, 'store']);

    Route::get('/sujets/{sujet_id}/messages', [MessagesForumController::class, 'index']);
    Route::post('/messages', [MessagesForumController::class, 'store']);
});



Route::middleware(['auth:sanctum', RoleMiddleware::class.':1'])->group(function () {
    Route::apiResource('/admin/matieres', AdminMatiereController::class); //V
    Route::apiResource('/admin/users', AdminUserController::class); //V

    Route::get('/admin/forums', [AdminForumController::class, 'index']);//V
    Route::post('/admin/forums', [AdminForumController::class, 'store']); //V
    Route::delete('/admin/forums/{id}', [AdminForumController::class, 'destroy']);
});



Route::middleware(['auth:sanctum', RoleMiddleware::class.':2'])->group(function () {
    Route::get('/professeur/matieres', [ProfesseurMatiereController::class, 'index']);
    Route::apiResource('/professeur/cours', ProfesseurCoursController::class); //V
    Route::apiResource('/professeur/quiz', ProfesseurQuizController::class); //V
    Route::get('/professeur/forums/sujets', [ProfesseurForumController::class, 'index']);
    Route::post('/professeur/forums/sujets/{sujet_id}/repondre', [ProfesseurForumController::class, 'repondre']);
    Route::get('/professeur/quiz/{quiz_id}/questions', [ProfesseurQuestionController::class, 'index']);
    Route::post('/professeur/quiz/{quiz_id}/questions', [ProfesseurQuestionController::class, 'store']);
    Route::put('/professeur/quiz/{quiz_id}/questions/{question_id}', [ProfesseurQuestionController::class, 'update']);
    Route::delete('/professeur/quiz/{quiz_id}/questions/{question_id}', [ProfesseurQuestionController::class, 'destroy']);

    // Réponses aux quiz
    Route::get('/professeur/questions/{question_id}/reponses', [\App\Http\Controllers\ProfesseurReponseQuizController::class, 'index']);
    Route::post('/professeur/questions/{question_id}/reponses', [\App\Http\Controllers\ProfesseurReponseQuizController::class, 'store']);
    Route::get('/professeur/reponses/{id}', [\App\Http\Controllers\ProfesseurReponseQuizController::class, 'show']);
    Route::put('/professeur/reponses/{id}', [\App\Http\Controllers\ProfesseurReponseQuizController::class, 'update']);
    Route::delete('/professeur/reponses/{id}', [\App\Http\Controllers\ProfesseurReponseQuizController::class, 'destroy']);
});

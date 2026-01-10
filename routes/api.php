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
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AdminMatiereController;
use App\Http\Controllers\AdminForumController;
use App\Http\Controllers\AdminImpersonationController;
use App\Http\Controllers\ProfesseurMatiereController;
use App\Http\Controllers\ProfesseurStudentsController;
use App\Http\Controllers\ProfesseurCoursController;
use App\Http\Controllers\ProfesseurQuizController;
use App\Http\Controllers\ProfesseurForumController;
use App\Http\Controllers\ProfesseurQuestionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\StudentMatiereController;


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/admin/impersonate/stop', [AdminImpersonationController::class, 'stop']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('update-password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', fn (Request $request) => $request->user())->name('me');
    Route::put('/me', [AuthController::class, 'updateProfile'])->name('update-profile');

    Route::get('/cours', [CoursController::class, 'index'])->name('cours.index');  // liste des cours
    Route::get('/cours/{id}', [CoursController::class, 'show'])->name('cours.show'); // détail cours
    Route::get('/student/matieres', [StudentMatiereController::class, 'index'])->name('student.matieres'); // matieres by classe

    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');        // liste des quiz pour l'élève
    Route::get('/quiz/{id}', [QuizController::class, 'show'])->name('quiz.show');   // détails d'un quiz

    Route::get('/quiz/{quiz_id}/questions', [QuestionController::class, 'index'])->name('quiz.questions.index');

    Route::get('/resultats', [ResultatsQuizController::class, 'index'])->name('resultats.index');
    Route::post('/resultats', [ResultatsQuizController::class, 'store'])->name('resultats.store');

    Route::get('/forums', [ForumController::class, 'index'])->name('forums.index');
    Route::get('/forums/{id}', [ForumController::class, 'show'])->name('forums.show');

    Route::get('/forums/{forum_id}/sujets', [SujetsForumController::class, 'index'])->name('forums.sujets.index');
    Route::post('/forums/sujets', [SujetsForumController::class, 'store'])->name('forums.sujets.store');

    Route::get('/sujets/{sujet_id}/messages', [MessagesForumController::class, 'index'])->name('sujets.messages.index');
    Route::post('/messages', [MessagesForumController::class, 'store'])->name('messages.store');
});



Route::middleware(['auth:sanctum', RoleMiddleware::class.':1'])->group(function () {
    Route::post('/admin/impersonate/{id}', [AdminImpersonationController::class, 'impersonate']);

    Route::apiResource('/admin/matieres', AdminMatiereController::class); //V
    Route::apiResource('/admin/users', AdminUserController::class); //V

    Route::get('/admin/forums', [AdminForumController::class, 'index']);//V
    Route::post('/admin/forums', [AdminForumController::class, 'store']); //V
    Route::delete('/admin/forums/{id}', [AdminForumController::class, 'destroy']);
});



Route::middleware(['auth:sanctum', RoleMiddleware::class.':1,2'])->group(function () {
    Route::get('/professeur/matieres', [ProfesseurMatiereController::class, 'index']);
    Route::get('/professeur/eleves', [ProfesseurStudentsController::class, 'getMyStudents']);
    Route::apiResource('/professeur/cours', ProfesseurCoursController::class); //V
    Route::apiResource('/professeur/quiz', ProfesseurQuizController::class); //V
    Route::get('/professeur/forums-list', [ProfesseurForumController::class, 'getForums']);
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

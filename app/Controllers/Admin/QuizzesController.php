<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookModel;
use App\Models\QuizModel;
use App\Models\QuizQuestionModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class QuizzesController extends BaseController
{
    protected QuizModel         $quizModel;
    protected QuizQuestionModel $questionModel;
    protected BookModel         $bookModel;

    public function __construct()
    {
        $this->quizModel     = new QuizModel();
        $this->questionModel = new QuizQuestionModel();
        $this->bookModel     = new BookModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');

        $query = $this->quizModel
            ->select('quizzes.*, books.title as book_title, books.author,
                      (SELECT COUNT(*) FROM quiz_questions WHERE quiz_questions.quiz_id = quizzes.id) as total_soal,
                      (SELECT COALESCE(SUM(points),0) FROM quiz_questions WHERE quiz_questions.quiz_id = quizzes.id) as total_poin')
            ->join('books', 'quizzes.book_id = books.id', 'LEFT')
            ->orderBy('quizzes.created_at', 'DESC');

        if ($search) {
            $query->groupStart()
                ->like('quizzes.name',  $search, insensitiveSearch: true)
                ->orLike('books.title', $search, insensitiveSearch: true)
                ->groupEnd();
        }

        $quizzes = $query->findAll();
        $books   = $this->bookModel->where('deleted_at', null)->orderBy('title', 'ASC')->findAll();

        return view('quizzes/index', [
            'quizzes' => $quizzes,
            'books'   => $books,
            'search'  => $search,
        ]);
    }

    public function store()
    {
        if (!$this->validate([
            'book_id'          => 'required|integer',
            'name'             => 'required|max_length[255]',
            'description'      => 'permit_empty|max_length[1000]',
            'duration_minutes' => 'required|integer|greater_than[0]',
            'max_attempts'     => 'required|integer|greater_than[0]',
        ])) {
            session()->setFlashdata(['msg' => implode(' ', $this->validator->getErrors()), 'error' => true]);
            return redirect()->back();
        }

        $this->quizModel->insert([
            'book_id'          => $this->request->getPost('book_id'),
            'name'             => $this->request->getPost('name'),
            'description'      => $this->request->getPost('description'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'max_attempts'     => $this->request->getPost('max_attempts'),
            'is_active'        => 1,
        ]);

        session()->setFlashdata(['msg' => 'Kuis berhasil ditambahkan.']);
        return redirect()->to('admin/kuis');
    }

    public function show($id = null)
    {
        $quiz = $this->quizModel
            ->select('quizzes.*, books.title as book_title, books.author,
                      (SELECT COALESCE(SUM(points),0) FROM quiz_questions WHERE quiz_questions.quiz_id = quizzes.id) as total_poin')
            ->join('books', 'quizzes.book_id = books.id', 'LEFT')
            ->where('quizzes.id', $id)
            ->first();

        if (empty($quiz)) throw new PageNotFoundException('Kuis tidak ditemukan');

        $questions = $this->questionModel
            ->where('quiz_id', $id)
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('quizzes/show', [
            'quiz'      => $quiz,
            'questions' => $questions,
        ]);
    }

    public function storeQuestion($quizId = null)
    {
        $quiz = $this->quizModel->find($quizId);
        if (empty($quiz)) throw new PageNotFoundException('Kuis tidak ditemukan');

        if (!$this->validate([
            'question'       => 'required',
            'option_a'       => 'required|max_length[500]',
            'option_b'       => 'required|max_length[500]',
            'option_c'       => 'required|max_length[500]',
            'option_d'       => 'required|max_length[500]',
            'correct_answer' => 'required|in_list[A,B,C,D]',
            'points'         => 'required|integer|greater_than[0]',
        ])) {
            session()->setFlashdata(['msg' => implode(' ', $this->validator->getErrors()), 'error' => true]);
            return redirect()->back();
        }

        $this->questionModel->insert([
            'quiz_id'        => $quizId,
            'question'       => $this->request->getPost('question'),
            'option_a'       => $this->request->getPost('option_a'),
            'option_b'       => $this->request->getPost('option_b'),
            'option_c'       => $this->request->getPost('option_c'),
            'option_d'       => $this->request->getPost('option_d'),
            'correct_answer' => $this->request->getPost('correct_answer'),
            'points'         => $this->request->getPost('points'),
        ]);

        session()->setFlashdata(['msg' => 'Soal berhasil ditambahkan.']);
        return redirect()->to("admin/kuis/{$quizId}");
    }

    // ── Edit soal — pakai POST biasa (bukan PUT) ────────────
    public function updateQuestion($quizId = null, $questionId = null)
    {
        $question = $this->questionModel
            ->where(['id' => $questionId, 'quiz_id' => $quizId])
            ->first();

        if (empty($question)) throw new PageNotFoundException('Pertanyaan tidak ditemukan');

        if (!$this->validate([
            'question'       => 'required',
            'option_a'       => 'required|max_length[500]',
            'option_b'       => 'required|max_length[500]',
            'option_c'       => 'required|max_length[500]',
            'option_d'       => 'required|max_length[500]',
            'correct_answer' => 'required|in_list[A,B,C,D]',
            'points'         => 'required|integer|greater_than[0]',
        ])) {
            session()->setFlashdata(['msg' => implode(' ', $this->validator->getErrors()), 'error' => true]);
            return redirect()->back();
        }

        $this->questionModel->update($questionId, [
            'question'       => $this->request->getPost('question'),
            'option_a'       => $this->request->getPost('option_a'),
            'option_b'       => $this->request->getPost('option_b'),
            'option_c'       => $this->request->getPost('option_c'),
            'option_d'       => $this->request->getPost('option_d'),
            'correct_answer' => $this->request->getPost('correct_answer'),
            'points'         => $this->request->getPost('points'),
        ]);

        session()->setFlashdata(['msg' => 'Soal berhasil diperbarui.']);
        return redirect()->to("admin/kuis/{$quizId}");
    }

    public function deleteQuestion($quizId = null, $questionId = null)
    {
        $question = $this->questionModel
            ->where(['id' => $questionId, 'quiz_id' => $quizId])
            ->first();

        if (empty($question)) throw new PageNotFoundException('Pertanyaan tidak ditemukan');

        $this->questionModel->delete($questionId);

        session()->setFlashdata(['msg' => 'Soal berhasil dihapus.']);
        return redirect()->to("admin/kuis/{$quizId}");
    }

    public function toggleActive($id = null)
    {
        $quiz = $this->quizModel->find($id);
        if (empty($quiz)) throw new PageNotFoundException('Kuis tidak ditemukan');

        $this->quizModel->update($id, ['is_active' => $quiz['is_active'] ? 0 : 1]);
        $status = $quiz['is_active'] ? 'dinonaktifkan' : 'diaktifkan';
        session()->setFlashdata(['msg' => "Kuis berhasil {$status}."]);
        return redirect()->back();
    }

    public function delete($id = null)
    {
        $quiz = $this->quizModel->find($id);
        if (empty($quiz)) throw new PageNotFoundException('Kuis tidak ditemukan');

        $this->quizModel->delete($id);
        session()->setFlashdata(['msg' => 'Kuis berhasil dihapus.']);
        return redirect()->to('admin/kuis');
    }
}
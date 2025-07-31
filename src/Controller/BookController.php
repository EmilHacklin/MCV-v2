<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\UploadManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    private const FILE_TYPES_SUPPORTED = ['image/jpeg'];

    /**
     * getInputs.
     *
     * Returns a array that contains all the user inputs
     *
     * @return array<mixed>
     */
    private function getInputs(Request $request): array
    {
        $id = htmlspecialchars((string) $request->request->get('id'));
        $title = htmlspecialchars((string) $request->request->get('title'));
        $isbn = htmlspecialchars((string) $request->request->get('isbn'));
        $author = htmlspecialchars((string) $request->request->get('author'));
        /** @var UploadedFile|null $file */
        $file = $request->files->get('img');

        $inputs = [
            'id' => $id,
            'title' => $title,
            'isbn' => $isbn,
            'author' => $author,
            'file' => $file,
        ];

        return $inputs;
    }

    /**
     * saveBook.
     *
     * Saves the book to database
     */
    private function saveBook(Request $request, ManagerRegistry $doctrine): void
    {
        // Get the inputs
        $inputs = $this->getInputs($request);
        /** @var string $title */
        $title = $inputs['title'];
        /** @var string $isbn */
        $isbn = $inputs['isbn'];
        /** @var string $author */
        $author = $inputs['author'];
        /** @var UploadedFile|null $file */
        $file = $inputs['file'];

        // Check if empty title
        if ('' === $title) {
            $this->addFlash(
                'warning',
                'Could not read the title!'
            );

            return;
        }

        $img = null;
        /** @var string $projectDir */
        $projectDir = $this->getParameter('kernel.project_dir');
        $uploadManager = new UploadManager($projectDir);

        try {
            $img = $uploadManager->saveUploadedFile($file, self::FILE_TYPES_SUPPORTED);
            // After setting $img
        } catch (\RuntimeException $e) {
            $this->addFlash(
                'warning',
                $e->getMessage()
            );

            return;
        }

        $bookRepository = new BookRepository($doctrine);

        $bookRepository->saveBook($title, $isbn, $author, $img);

        $this->addFlash(
            'notice',
            'You have gone created a book!'
        );
    }

    /**
     * updateBook.
     *
     * Update the book in database
     */
    private function updateBook(Request $request, ManagerRegistry $doctrine): void
    {
        $inputs = $this->getInputs($request);
        /** @var string $id */
        $id = $inputs['id'];
        /** @var string $title */
        $title = $inputs['title'];
        /** @var string $isbn */
        $isbn = $inputs['isbn'];
        /** @var string $author */
        $author = $inputs['author'];
        /** @var UploadedFile|null $file */
        $file = $inputs['file'];

        // Check if empty id
        if ('' === $id) {
            $this->addFlash(
                'warning',
                'Could not read the id!'
            );

            return;
        }

        // Retrieve the existing book entity
        $bookRepository = new BookRepository($doctrine);

        $book = $bookRepository->find($id);

        // Check if book exists
        if (!$book) {
            $this->addFlash(
                'warning',
                'Book not found with ID '.$id
            );

            return;
        }

        // Check if empty title
        if ('' === $title) {
            $this->addFlash(
                'warning',
                'Could not read the title!'
            );

            return;
        }

        // Save new image
        $img = null;
        /** @var string $projectDir */
        $projectDir = $this->getParameter('kernel.project_dir');
        $uploadManager = new UploadManager($projectDir);

        try {
            $img = $uploadManager->saveUploadedFile($file, self::FILE_TYPES_SUPPORTED);
            // After setting $img
        } catch (\RuntimeException $e) {
            $this->addFlash(
                'warning',
                $e->getMessage()
            );

            return;
        }

        // If you have new image
        if (null !== $img) {
            // If old image existed remove it
            $oldImage = $book->getImg();
            if (null !== $oldImage) {
                if (!$uploadManager->deleteUploadedFile($oldImage)) {
                    $this->addFlash(
                        'notice',
                        'Could not remove old img!'
                    );
                }
            }
        }

        // Update the book
        $bookRepository->updateBook((int) $id, $title, $isbn, $author, $img);

        $this->addFlash(
            'notice',
            'The book has been updated successfully!'
        );
    }

    /**
     * deleteBook.
     *
     * Update the book in database
     */
    private function deleteBook(int $id, ManagerRegistry $doctrine): void
    {
        // Retrieve the existing book entity
        $bookRepository = new BookRepository($doctrine);

        $book = $bookRepository->find($id);

        // Check if book don't exists
        if (!$book) {
            $this->addFlash(
                'warning',
                'Book not found with ID '.$id
            );

            return;
        }

        // Delete image
        $img = $book->getImg();
        /** @var string $projectDir */
        $projectDir = $this->getParameter('kernel.project_dir');
        $uploadManager = new UploadManager($projectDir);

        if (null !== $img) {
            if (!$uploadManager->deleteUploadedFile($img)) {
                $this->addFlash(
                    'notice',
                    'Could not remove img: '.$img
                );
            }
        }

        $bookRepository->deleteBook($id);

        $this->addFlash(
            'notice',
            'The book has been deleted!'
        );
    }

    #[Route('/library', name: 'library')]
    public function library(): Response
    {
        return $this->render('book/index.html.twig');
    }

    #[Route('/book/create', name: 'book_create_get', methods: 'GET')]
    public function createBookGET(): Response
    {
        return $this->render('book/create.html.twig');
    }

    #[Route('/book/create', name: 'book_create_post', methods: 'POST')]
    public function createBookPOST(
        Request $request,
        ManagerRegistry $doctrine,
    ): Response {
        // Save book to database
        $this->saveBook($request, $doctrine);

        return $this->redirectToRoute('book_create_get');
    }

    #[Route('/book/show', name: 'book_show_all')]
    public function showAllBooks(
        ManagerRegistry $doctrine,
    ): Response {
        $bookRepository = new BookRepository($doctrine);

        $data = $bookRepository->readAllBooks();

        return $this->render('book/show.html.twig', $data);
    }

    #[Route('/book/show/{id<\d+>}', name: 'book_show_one')]
    public function showOneBook(
        int $id,
        ManagerRegistry $doctrine,
    ): Response {
        $bookRepository = new BookRepository($doctrine);

        $data = $bookRepository->readOneBook($id);

        return $this->render('book/show_one.html.twig', $data);
    }

    #[Route('/book/show/update', name: 'book_show_all_update')]
    public function showAllBooksUpdate(
        ManagerRegistry $doctrine,
    ): Response {
        $bookRepository = new BookRepository($doctrine);

        $data = $bookRepository->readAllBooks();

        return $this->render('book/show_update.html.twig', $data);
    }

    #[Route('/book/update/{id<\d+>}', name: 'book_update_get', methods: ['GET'])]
    public function showAllBooksUpdateGET(
        int $id,
        ManagerRegistry $doctrine,
    ): Response {
        $bookRepository = new BookRepository($doctrine);

        $data = $bookRepository->readOneBook($id);

        return $this->render('book/update.html.twig', $data);
    }

    #[Route('/book/update', name: 'book_update_post', methods: ['POST'])]
    public function showAllBooksUpdatePOST(
        Request $request,
        ManagerRegistry $doctrine,
    ): Response {
        $inputs = $this->getInputs($request);

        // Update book to database
        $this->updateBook($request, $doctrine);

        return $this->redirectToRoute('book_update_get', ['id' => $inputs['id']]);
    }

    #[Route('/book/show/delete', name: 'book_show_all_delete_get', methods: ['GET'])]
    public function showAllBooksDeleteGET(
        ManagerRegistry $doctrine,
    ): Response {
        $bookRepository = new BookRepository($doctrine);

        $data = $bookRepository->readAllBooks();

        return $this->render('book/show_delete.html.twig', $data);
    }

    #[Route('/book/show/delete/{id<\d+>}', name: 'book_delete_post', methods: ['POST'])]
    public function showAllBooksDeletePOST(
        int $id,
        ManagerRegistry $doctrine,
    ): Response {
        // Delete book to database
        $this->deleteBook($id, $doctrine);

        return $this->redirectToRoute('book_show_all_delete_get');
    }
}

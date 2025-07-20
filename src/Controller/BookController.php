<?php

namespace App\Controller;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;

final class BookController extends AbstractController
{
    /**
     * saveBook
     *
     * Saves the book to database
     *
     * @param  Request $request
     * @param  ManagerRegistry $doctrine
     * @param  BookRepository $bookRepository
     * @return void
     */
    private function saveBook(Request $request, ManagerRegistry $doctrine, BookRepository $bookRepository): void
    {
        $title = htmlspecialchars((string) $request->request->get('title'));
        if ($title === '') {
            $this->addFlash(
                'warning',
                'Could not read the title!'
            );
            return;
        }

        $isbn = htmlspecialchars((string) $request->request->get('isbn'));
        $author = htmlspecialchars((string) $request->request->get('author'));

        /** @var UploadedFile|null $file */
        $file = $request->files->get('img');
        $img = null;
        if ($file instanceof UploadedFile) {
            try {
                $img = $this->saveUploadedJpeg($file);
                // After setting $img
            } catch (RuntimeException $e) {
                $this->addFlash(
                    'warning',
                    $e->getMessage()
                );
                return;
            }
        }

        $book = $bookRepository->returnBook($title, $isbn, $author, $img);

        $entityManager = $doctrine->getManager();

        // tell Doctrine you want to (eventually) save the Book
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'You have gone created a book!'
        );
    }

    /**
     * updateBook
     *
     * Update the book in database
     *
     * @param  Request $request
     * @param  ManagerRegistry $doctrine
     * @param  BookRepository $bookRepository
     * @return void
     */
    private function updateBook(Request $request, ManagerRegistry $doctrine, BookRepository $bookRepository): void
    {
        $id = htmlspecialchars((string) $request->request->get('id'));
        if ($id === '') {
            $this->addFlash(
                'warning',
                'Could not read the id!'
            );
            return;
        }

        // Retrieve the existing book entity
        $book = $bookRepository->find($id);

        if (!$book) {
            $this->addFlash(
                'warning',
                'Book not found with ID '. $id
            );
            return;
        }

        $title = htmlspecialchars((string) $request->request->get('title'));
        if ($title === '') {
            $this->addFlash(
                'warning',
                'Could not read the title!'
            );
            return;
        }

        $isbn = htmlspecialchars((string) $request->request->get('isbn'));
        $author = htmlspecialchars((string) $request->request->get('author'));

        /** @var UploadedFile|null $file */
        $file = $request->files->get('img');
        $img = null;
        if ($file instanceof UploadedFile) {
            try {
                $img = $this->saveUploadedJpeg($file);
                // After setting $img
            } catch (RuntimeException $e) {
                $this->addFlash(
                    'warning',
                    $e->getMessage()
                );
                return;
            }
            if ($book->getImg() !== null) {
                if (!$this->deleteUploadedJpeg($book->getImg())) {
                    $this->addFlash(
                        'notice',
                        'Could not remove old img!'
                    );
                }
            }
        }

        // Update the book properties
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setImg($img);

        // Get the entity manager
        $entityManager = $doctrine->getManager();

        // Persist is optional for existing entities, but it doesn't hurt
        $entityManager->persist($book);

        // Flush to save changes
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'The book has been updated successfully!'
        );
    }

    /**
     * deleteBook
     *
     * Update the book in database
     *
     * @param  int $id
     * @param  ManagerRegistry $doctrine
     * @param  BookRepository $bookRepository
     * @return void
     */
    private function deleteBook(int $id, ManagerRegistry $doctrine, BookRepository $bookRepository): void
    {
        // Retrieve the existing book entity
        $book = $bookRepository->find($id);
        if (!$book) {
            $this->addFlash(
                'warning',
                'Book not found with ID '. $id
            );
            return;
        }

        if ($book->getImg() !== null) {
            if (!$this->deleteUploadedJpeg($book->getImg())) {
                $this->addFlash(
                    'notice',
                    'Could not remove old img: ' . $book->getImg()
                );
            }
        }

        // Get the entity manager
        $entityManager = $doctrine->getManager();

        // Remove the book
        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'The book has been deleted!'
        );
    }

    /**
     * saveUploadedJpeg
     *
     * Saves the uploaded JPEG file to the /public/uploads folder and returns the path
     *
     * @param  ?UploadedFile $file
     * @return string
     */
    private function saveUploadedJpeg(?UploadedFile $file): ?string
    {
        $img = null;

        if ($file instanceof UploadedFile && $file->isValid()) {
            if ($file->getMimeType() !== 'image/jpeg') {
                throw new RuntimeException('Invalid file type. Please upload a JPEG image.');
            }
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            // Move the file to the drectory
            try {
                $projectDir = $this->getParameter('kernel.project_dir');
                if (!is_string($projectDir)) {
                    throw new RuntimeException('The kernel.project_dir parameter is not set or not a string.');
                }
                $targetDirectory = $projectDir . '/public/uploads';

                // Make sure the directory exists
                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }

                // Check write permissions
                if (!is_writable($targetDirectory)) {
                    throw new RuntimeException('Uploads directory is not writable.');
                }

                $file->move($targetDirectory, $newFilename);
                // Save $newFilename and path to database
                $img = '/uploads/' . $newFilename;
            } catch (FileException $e) {
                throw new RuntimeException('Error uploading file: '.$e->getMessage());
            }
        }
        return $img;
    }

    /**
     * deleteUploadedJpeg
     *
     * Deletes the image file at the given path within the uploads directory.
     *
     * @param string $img The relative path to the image, e.g., '/uploads/filename.jpg'
     * @return bool True if deletion was successful, false otherwise
     */
    private function deleteUploadedJpeg(string $img): bool
    {
        // Get the project directory
        $projectDir = $this->getParameter('kernel.project_dir');
        if (!is_string($projectDir)) {
            throw new RuntimeException('The kernel.project_dir parameter is not set or not a string.');
        }

        // Full path to the file
        $fullPath = $projectDir . '/public' . $img;

        // Check if file exists
        if (!file_exists($fullPath)) {
            // File does not exist
            return false;
        }

        // Attempt to delete the file
        try {
            return unlink($fullPath);
        } catch (RuntimeException $e) {
            // Handle exceptions if needed
            return false;
        }
    }


    #[Route('/library', name: 'library')]
    public function library(): Response
    {
        return $this->render('book/index.html.twig');
    }

    #[Route('/book/create', name: 'book_create_get', methods: 'GET')]
    public function createBook(): Response
    {
        return $this->render('book/create.html.twig');
    }

    #[Route('/book/create', name: 'book_create_post', methods: 'POST')]
    public function createBookPost(
        Request $request,
        ManagerRegistry $doctrine,
        BookRepository $bookRepository
    ): Response {
        // Save book to database
        $this->saveBook($request, $doctrine, $bookRepository);

        return $this->redirectToRoute('book_create_get');
    }

    #[Route('/book/show', name: 'book_show_all')]
    public function showAllBooks(
        BookRepository $bookRepository
    ): Response {
        $data = $bookRepository->readAllBooks();

        return $this->render('book/show.html.twig', $data);
    }

    #[Route('/book/show/{id<\d+>}', name: 'book_show_one')]
    public function showOneBook(
        int $id,
        BookRepository $bookRepository
    ): Response {
        $data = $bookRepository->readOneBook($id);

        return $this->render('book/show_one.html.twig', $data);
    }


    #[Route('/book/show/update', name: 'book_show_all_update')]
    public function showAllBooksUpdate(
        BookRepository $bookRepository
    ): Response {
        $data = $bookRepository->readAllBooks();

        return $this->render('book/show_update.html.twig', $data);
    }

    #[Route('/book/update/{id<\d+>}', name: 'book_update_get', methods: ['GET'])]
    public function showAllBooksUpdateSpecific(
        int $id,
        BookRepository $bookRepository
    ): Response {
        $data = $bookRepository->readOneBook($id);

        return $this->render('book/update.html.twig', $data);
    }

    #[Route('/book/update', name: 'book_update_post', methods: ['POST'])]
    public function showAllBooksUpdatePost(
        Request $request,
        ManagerRegistry $doctrine,
        BookRepository $bookRepository
    ): Response {
        $id = htmlspecialchars((string) $request->request->get('id'));

        // Update book to database
        $this->updateBook($request, $doctrine, $bookRepository);

        return $this->redirectToRoute('book_update_get', ['id' => $id]);
    }

    #[Route('/book/show/delete', name: 'book_show_all_delete_get', methods: ['GET'])]
    public function showAllBooksDeleteGET(
        BookRepository $bookRepository
    ): Response {
        $data = $bookRepository->readAllBooks();

        return $this->render('book/show_delete.html.twig', $data);
    }

    #[Route('/book/show/delete/{id<\d+>}', name: 'book_delete_post', methods: ['POST'])]
    public function showAllBooksDeletePOST(
        int $id,
        ManagerRegistry $doctrine,
        BookRepository $bookRepository
    ): Response {
        // Delete book to database
        $this->deleteBook($id, $doctrine, $bookRepository);

        return $this->redirectToRoute('book_show_all_delete_get');
    }
}

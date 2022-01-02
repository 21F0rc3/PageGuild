<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BookController;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

use App\Models\Book;
use App\Models\Item;
use App\Models\AuthorBook;
use App\Models\GenreBook;
use App\Models\Genre;
use App\Models\ItemType;
use App\Models\Publisher;
use App\Models\Language;
use App\Models\Author;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\AuthorController;
use Illuminate\Support\Facades\Session;

class ItemController extends Controller
{
    /**
     * Returns item type detail page of specific item
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showDetails($id)
    {
        // Vai buscar o item e o tipo do item
        $item = Item::find($id);
        $itemType = ItemType::find($item->item_type_id);

        // Procura por tipo de artigo
        switch($item->item_type_id) {
            case 1: { // Livro
                $book = Book::find($id);

                // No caso do livro vai buscar ainda os authors e os genres
                $authors = BookController::getBookAuthors($id);
                $genres = BookController::getBookGenres($id);

                // Vai buscar o editor
                $publisher = Publisher::find($book->publisher_id);

                // Vai buscar o idioma
                $language = Language::find($book->language_id);

                return view('bookDetails', ['item' => $item,
                                            'itemType' => $itemType,
                                            'book' => $book,
                                            'authors' => $authors,
                                            'genres' => $genres,
                                            // Verifica se o publisher existe. Se sim, envia o nome
                                            'publisher' => $publisher == null ? "Não tem" : $publisher->name,
                                            'language' => $language
                                            ]);
            }
            case 2: {
                //
            }
        }
    }

    /**
     * Funcao chamada quando efetuamos uma pesquisa sem filtros
     * Funcao chamada pela rota /search/results
     */
    public function defaultSearch(Request $request) {
        return ItemController::searchItems($request->search);
    }

    /**
     * Funcao chamada quando efetuamos uma pesquisa com filtros ou ordenações, ou as duas
     * Funcao chamada pela rota /search/results/orderFilter/
     */
    public function orderFilterSearch(string $searchQuery, int $author_id, int $publisher_id, int $genre_id, int $publication_year, string $order_by, string $order_direction) {
        return ItemController::searchItems($searchQuery, author_id: $author_id, publisher_id: $publisher_id, genre_id: $genre_id,year: $publication_year, order_by: $order_by, order_direction: $order_direction);
    }

    /**
     * Procura por todos os tipos de item
     * Procura tambem resultados por filtro
     *
     * @param $searchQuery
     * @param $filter - Array com o tipo de filtro, e id do filtro(id do autor, id do editor, etc...) que esta a ser aplicado aos resultados. Por default e null ou 0 para ignorar a filtragem
     */
    private function searchItems(string $searchQuery,
                                 int $author_id = null, int $publisher_id = null,
                                 int $genre_id = null, int $year = null,
                                 string $order_by = null, string $order_direction = 'asc')
    {
        /**
         * Procura por todas as referencias relacionadas aos livros
         *
         * Se tiver aplicado uma ordenacao faz a pesquisa com ordenacao
         *
         */
        $results = BookController::buildSearchBooksQuery($searchQuery, ['book.item_id as item_id', 'book.title as item_name'],
            author_id: $author_id, genre_id: $genre_id, publisher_id: $publisher_id, year: $year);

        if($order_by != 'null' && $order_by != null) {
            $results = $results->orderBy($order_by, $order_direction);
        }

        /**
         * Vai buscar todos os filtros que sao possiveis aplicar aos resultados
         * Vai ser utilizado para popular dinamicamente o conteudo nos accordion de filtros
         */
        $possibleFilterOptions = [
            ['name' => 'author', 'options' => ItemController::getFilterOptions($results, ['author.id','author.name'])],
            ['name' => 'publisher', 'options' => ItemController::getFilterOptions($results, ['publisher.id','publisher.name'])],
            ['name' => 'genre', 'options' => ItemController::getFilterOptions($results, ['genre.id','genre.name'])],
            ['name' => 'year', 'options' => ItemController::getFilterOptions($results, ['book.publication_year', 'book.publication_year'])],
        ];

        /**
         * Aqui são guardadados todos os dados do url atual
         * E utilizado para manter filtros.
         * Por exemplo.: search/results/filter/{substring}/1/0, ele vai filtrar os resultados de substring pelos livros escritos por o autor.id = 1
         *               sarch/results/filter/{substring}/1/1, aos resultados anteriores ele vai aplicar um novo filtro pelos livros publicados pela publisher.id = 1
         *
         * Quando o valor e 0 significa que nenhum filtro foi aplicado
         *
         */
        $url = [
            "searchQuery" => $searchQuery,
            "filters" => [
                'author' => $author_id,
                'publisher' => $publisher_id,
                'genre' => $genre_id,
                'year' => $year
            ],
            "order_by" => $order_by,
            "order_direction" => $order_direction
           ];

        return view('search/results', ['url' => $url, 'results' => $results->get(), 'possibleFilterOptions' => $possibleFilterOptions]);
    }

    /**
     * Dentre os resultados da pesquisa atual, essa funcao busca os filtros possiveis
     * baseado nas colunas especificadas em @param $filterColumns
     *
     * Esta função serve para popular o accordion dos diversos filtros (autores, editores, etc...)
     *
     */
    private function getFilterOptions(Builder $query, array $filterColumns): \Illuminate\Support\Collection
    {
        $filterOptionsQuery = $query->clone();
        $filterOptionsQuery->columns = ["{$filterColumns[0]} as filter_option", "{$filterColumns[1]} as option_desc"];

        return $filterOptionsQuery
                /**
                * Agrupa por id's
                */
                ->groupBy('filter_option')
                /**
                * Adiciona um count
                */
                ->addSelect(DB::raw('count('.$filterColumns[0].')'))
                ->get();
    }
}

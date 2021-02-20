<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/news.php";


class NewsList
    /**
     * class for managing how many news that should be shown and
     * managing navigation buttons for the different news pages.
     */
{
    private $news;
    private $limit;
    private $amountOfNews;
    private $page;

    public function __construct(int $limit, int $page)
        /**
         * requires a limit for how many news that should be shown per page
         * and a page number to be able to tell which pages to show.
         *
         * opens a manager to collect news from the database,
         * does some calculations to determine which news to get
         * and requests the news to get from teh database,
         */
    {
        $manager = new Manager();
        $this->limit = $limit;
        $this->amountOfNews = $manager->getAmountOfNews();
        $amountOfPages = $this->getAmountOfPages();
        if ($page > $amountOfPages) {
            $this->page = $amountOfPages;
        } else {
            $this->page = $page;
        }
        $this->news = $manager->getNewsN($limit, $limit * $page);
    }

    public function getAmountOfPages(): int
        /**
         * calculates the amount of pages that should exist based on
         * the limit and total amount of news in teh database.
         */
    {
        return floor($this->amountOfNews / $this->limit);
    }

    public function toHTML(): string
        /**
         * returns an HTML block representing the news that should be shown on this page
         *
         * call all the internal news objects toSortHTML methods.
         * the toShortHTML methods only contains the news articles title and preamble
         */
    {
        $html = "";
        foreach ($this->news as $news) {
            $html .= $news->toShortHTML();
        }
        return $html;
    }

    public function pageNavigationHTML(): string
        /**
         * returns an HTML block for the navigation arrows that should pre present on the page
         *
         * only gives a next button if there is a next page and
         * only a previous button if there is a previous page.
         */
    {
        $html = "";
        if (0 < $this->page) {
            $previous = $this->page - 1;
            if ($previous === 0) {
                $html .= "<a class='button' href='./'>Förra sidan</a>";
            } else {
                $html .= "<a class='button' href='./?$previous'>Förra sidan</a>";
            }
        }
        if ($this->page < $this->getAmountOfPages()) {
            $next = $this->page + 1;
            $html .= "<a class='button' href='./?$next'>Nästa sida</a>";
        }
        if ($html) {
            $html = "<nav class='page-navigation'>$html</nav>";
        }
        return $html;
    }
}
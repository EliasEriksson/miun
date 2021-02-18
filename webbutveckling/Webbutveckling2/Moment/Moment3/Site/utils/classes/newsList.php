<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/news.php";


class NewsList {
    private $news;
    private $limit;
    private $amountOfNews;
    private $page;

    public function __construct(int $limit, int $page)
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

        $this->news = $manager->getNewsN($limit, $limit*$page);
    }

    public function getAmountOfPages(): int {
        return ceil($this->amountOfNews / $this->limit);
    }

    public function toHTML(): string {
        $html = "";
        foreach ($this->news as $news) {
            $html .= $news->toShortHTML();
        }
        return $html;
    }

    public function pageNavigationHTML(): string {
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
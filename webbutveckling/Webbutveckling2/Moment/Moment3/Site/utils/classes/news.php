<?php

include_once __DIR__ . "/../config.php";

class News
    /**
     * class for representing a news article
     */
{
    private $id;
    private $title;
    private $preamble;
    private $content;
    private $postDate;
    private $lastEdited;

    public function __construct(array $newsData)
        /**
         * constructs from an associative array with keys:
         * id, title, preamble, content, postDate and lastEdited
         *
         * lastEdited will be null unless the article have been edited
         */
    {
        $this->id = $newsData["id"];
        $this->title = $newsData["title"];
        $this->preamble = $newsData["preamble"];
        $this->content = $newsData["content"];
        $this->postDate = $this->timeToDatetime($newsData["postDate"]);
        $this->lastEdited = $this->timeToDatetime($newsData["lastEdited"]);

    }

    private function timeToDatetime(?string $datetimeString): ?DateTime
        /**
         * constructs a timestamp object from the given time with timezone
         * UTC since that's what mariaDB uses by default
         */
    {
        if ($datetimeString) {
            return DateTime::createFromFormat("Y-m-d H:i:s", $datetimeString, new DateTimeZone("UTC"));
        }
        return null;
    }

    private function formatArticleHeading(int $headingGrade): string
        /**
         * helper method that gives an HTML block for the title with specified heading grade (h1-h6)
         *
         * headingGrade should be an integer >= 1 and <= 6
         */
    {
        return "<h$headingGrade class='article-heading'>$this->title</h$headingGrade>";
    }

    private function formatArticlePreamble(): string
        /**
         * helper method that gives an HTML block for the preamble
         */

    {
        return "<p>$this->preamble</p>";
    }

    private function formatArticleContent(): string
        /**
         * helper method that gives an HTML block for the content
         */
    {
        return "<p>$this->content</p>";
    }

    private function formatPostTime(): string
        /**
         * helper method that gives an HTML block for the postTime
         */
    {
        return "<span class='article-time-label'>Postat: <span class='article-time'>" . $this->postDate->getTimestamp() . "</span></span>";
    }

    private function formatLastEdited(): string
        /**
         * helper method that gives an HTML block for the lastEdited time if applicable
         */
    {
        if ($this->lastEdited) {
            $lastEdited = $this->lastEdited->getTimestamp();
            return "<span class='article-time-label'>Redigerat: <time class='article-time'>$lastEdited</time></span>";
        }
        return "";
    }

    private function formatButtons(bool $readMore): string
        /**
         * helper method that gives an HTML block for the navigation buttons associated with the article
         *
         * will only generate the read more button if readMore is true
         * will only add an edit and remove option if the user is an admin.
         */
    {
        $newsURL = $GLOBALS["rootURL"] . "/nyheter/nyhet/?$this->id";
        $html = "<div class='article-buttons'>";
        if ($readMore) {
            $html .= "<a class='button' href='$newsURL'>Läs mera...</a>";
        }
        if (isset($_SESSION["admin"])) {
            $editURL = $GLOBALS["rootURL"] . "/admin/redigera/?$this->id";
            $html .= "<a class='button' href='$editURL'>Redigera</a>
                      <form method='post' enctype='application/x-www-form-urlencoded'>
                          <input name='id' value='$this->id' type='hidden'>
                          <input class='button' name='delete' type='submit' value='radera'>
                      </form>";
        }
        $html .= "</div>";
        return $html;
    }

    public function toShortHTML(): string
        /**
         * method that generates the HTML used for news lists
         */
    {
        $html = "<div class='article'><article class='news-article'>";
        $html .= $this->formatArticleHeading(2);
        $html .= $this->formatPostTime();
        $html .= $this->formatLastEdited();
        $html .= $this->formatArticlePreamble();
        $html .= "</article>";
        $html .= $this->formatButtons(true);
        $html .= "</div>";
        return $html;
    }

    public function toLongHTML(): string
        /**
         * method that generates teh HTML for a full article
         */
    {
        $html = "<article class='news-article'>";
        $html .= $this->formatArticleHeading(1);
        $html .= $this->formatPostTime();
        $html .= $this->formatLastEdited();
        $html .= $this->formatArticlePreamble();
        $html .= $this->formatArticleContent();
        $html .= "</article>";
        $html .= $this->formatButtons(false);
        return $html;
    }

    /**
     * getters
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPostDate(): DateTime
    {
        return $this->postDate;
    }

    public function getLastEdited(): DateTime
    {
        return $this->lastEdited;
    }

    public function getPreamble(): string
    {
        return $this->preamble;
    }
}
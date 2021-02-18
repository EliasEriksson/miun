<?php

include_once __DIR__ . "/../config.php";

class News
{
    private $id;
    private $title;
    private $preamble;
    private $content;
    private $postDate;
    private $lastEdited;

    public function __construct(array $newsData)
    {
        $this->id = $newsData["id"];
        $this->title = $newsData["title"];
        $this->preamble = $newsData["preamble"];
        $this->content = $newsData["content"];
        $this->postDate = $this->timeToDatetime($newsData["postDate"]);
        $this->lastEdited = $this->timeToDatetime($newsData["lastEdited"]);

    }

    private function timeToDatetime(?string $datetimeString): ?DateTime
    {
        if ($datetimeString) {
            return DateTime::createFromFormat("Y-m-d H:i:s", $datetimeString, new DateTimeZone("UTC"));
        }
        return null;
    }

    private function formatArticleHeading(): string
    {
        return "<h2 class='article-heading'>$this->title</h2>";
    }

    private function formatArticlePreamble(): string {
        return "<p>$this->preamble</p>";
    }

    private function formatArticleContent(): string {
        return "<p>$this->content</p>";
    }

    private function formatPostTime(): string
    {
        return "<span class='article-time-label'>Postat: <span class='article-time'>" . $this->postDate->getTimestamp() . "</span></span>";
    }

    private function formatLastEdited(): string
    {
        if ($this->lastEdited) {
            return "<span class='article-time-label'>Redigerat<time class='article-time'>.$this->lastEdited.</time></span>";
        }
        return "";
    }

    private function formatButtons(): string
    {
        $newsURL = $GLOBALS["rootURL"] . "/nyheter/nyhet/?$this->id";
        $html = "<div class='edit-buttons'>";
        $html .= "<a class='button' href='$newsURL'>Läs mera...</a>";
        if (isset($_SESSION["admin"])) {

            $editURL = $GLOBALS["rootURL"] . "/admin/redigera/";

            $html .= "<form action='$editURL' method='post' enctype='application/x-www-form-urlencoded'>
                        <input name='id' type='hidden'>
                        <input class='button' name='edit' type='submit' value='Redigera'>
                     </form>
                        <form method='post' enctype='application/x-www-form-urlencoded'>
                        <input name='id' value='$this->id' type='hidden'>
                        <input class='button' name='delete' type='submit' value='radera'>
                     </form>";
        }
        $html .= "</div>";
        return $html;
    }

    public function toShortHTML(): string
    {
        $html = "<article class='news-article'>";
        $html .= $this->formatArticleHeading();
        $html .= $this->formatPostTime();
        $html .= $this->formatLastEdited();
        $html .= $this->formatArticlePreamble();
        $html .= "</article>";
        $html .= $this->formatButtons();
        return $html;
    }

    public function toLongHTML(): string
    {
        $html = "<article class='news-article'>";
        $html .= $this->formatArticleHeading();
        $html .= $this->formatPostTime();
        $html .= $this->formatLastEdited();
        $html .= $this->formatArticlePreamble();
        $html .= $this->formatArticleContent();
        $html .= "</article>";
        $html .= $this->formatButtons();
        return $html;
    }

    /**
     * @return int
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return DateTime
     */
    public function getPostDate(): DateTime
    {
        return $this->postDate;
    }

    /**
     * @return DateTime
     */
    public function getLastEdited(): DateTime
    {
        return $this->lastEdited;
    }
}
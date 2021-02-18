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

    private function formatArticleHeading(int $headingGrade): string
    {
        return "<h$headingGrade class='article-heading'>$this->title</h$headingGrade>";
    }

    private function formatArticlePreamble(): string
    {
        return "<p>$this->preamble</p>";
    }

    private function formatArticleContent(): string
    {
        return "<p>$this->content</p>";
    }

    private function formatPostTime(): string
    {
        return "<span class='article-time-label'>Postat: <span class='article-time'>" . $this->postDate->getTimestamp() . "</span></span>";
    }

    private function formatLastEdited(): string
    {
        if ($this->lastEdited) {
            $lastEdited = $this->lastEdited->getTimestamp();
            return "<span class='article-time-label'>Redigerat: <time class='article-time'>$lastEdited</time></span>";
        }
        return "";
    }

    private function formatButtons(bool $readMore): string
    {
        $newsURL = $GLOBALS["rootURL"] . "/nyheter/nyhet/?$this->id";
        $html = "<div class='edit-buttons'>";
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
    {
        $html = "<article class='news-article'>";
        $html .= $this->formatArticleHeading(2);
        $html .= $this->formatPostTime();
        $html .= $this->formatLastEdited();
        $html .= $this->formatArticlePreamble();
        $html .= "</article>";
        $html .= $this->formatButtons(true);
        return $html;
    }

    public function toLongHTML(): string
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

    public function getPreamble()
    {
        return $this->preamble;
    }
}
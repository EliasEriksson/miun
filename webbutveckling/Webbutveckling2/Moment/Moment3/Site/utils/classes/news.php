<?php

include_once __DIR__ . "/../config.php";

/**
 * Class News
 * class for representing a news article
 *
 * @property string $id the news articles id
 * @property string $title the news articles title
 * @property string $preamble the news articles preamble
 * @property string $content the news articles main body content
 * @property DateTime $postDate the date the news article was posted
 * @property ?DateTime $lastEdited the last time the news article was posted, null if it was never edited
 */
class News
{
    private $id;
    private $title;
    private $preamble;
    private $content;
    private $postDate;
    private $lastEdited;

    /**
     * News constructor.
     *
     * @param array $newsData array[id, title, preamble, content, postDate, lastEdited]
     */
    public function __construct(array $newsData)
    {
        $this->id = $newsData["id"];
        $this->title = $newsData["title"];
        $this->preamble = $newsData["preamble"];
        $this->content = $newsData["content"];
        $this->postDate = $this->timeToDatetime($newsData["postDate"]);
        $this->lastEdited = $this->timeToDatetime($newsData["lastEdited"]);

    }

    /**
     * constructs a timestamp object form the given time with timezone UTC
     * UTC is the timezone since that is the mariaDB default
     *
     * @param string|null $datetimeString date string with format %Y-%m-%d %H:%i:%s
     * @return DateTime|null if the given date was not null a DateTime will be returned
     */
    private function timeToDatetime(?string $datetimeString): ?DateTime
    {
        if ($datetimeString) {
            return DateTime::createFromFormat("Y-m-d H:i:s", $datetimeString, new DateTimeZone("UTC"));
        }
        return null;
    }

    /**
     * helper method that gives an HTML block for the title
     *
     * @param int $headingGrade h element grade (1-6)
     * @return string the heading element
     */
    private function formatArticleHeading(int $headingGrade): string
    {
        return "<h$headingGrade class='article-heading'>$this->title</h$headingGrade>";
    }

    /**
     * helper method that vies the HTML block for the preamble
     *
     * @return string the preamble paragraph element
     */
    private function formatArticlePreamble(): string
    {
        return "<p>$this->preamble</p>";
    }

    /**
     * helper method that gives an HTML block for the content
     *
     * @return string the content paragraph element
     */
    private function formatArticleContent(): string
    {
        return "<p>$this->content</p>";
    }

    /**
     * helper method that gives an HTML block for the postTime
     *
     * @return string the time element
     */
    private function formatPostTime(): string
    {
        return "<span class='article-time-label'>Postat: <span class='article-time'>" . $this->postDate->getTimestamp() . "</span></span>";
    }

    /**
     * helper method that gives an HTML block for the lastEdited time if applicable
     * @return string the time element
     */
    private function formatLastEdited(): string
    {
        if ($this->lastEdited) {
            $lastEdited = $this->lastEdited->getTimestamp();
            return "<span class='article-time-label'>Redigerat: <time class='article-time'>$lastEdited</time></span>";
        }
        return "";
    }

    /**
     * helper method that gives an HTML block for the navigation buttons associated with the article
     *
     * will only generate the read more button if readMore is true
     * will only add an edit and remove option if the user is an admin.
     *
     * @param bool $readMore if readMore button should be generated
     * @return string the button elements
     */
    private function formatButtons(bool $readMore): string
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

    /**
     * method that generates the HTML used for news lists
     *
     * @return string list article HTML
     */
    public function toShortHTML(): string
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

    /**
     * method that generates teh HTML for a full article
     *
     * @return string full article HTML
     */
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

    // getters

    /**
     * @return string the news articles id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string the news articles title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string the nes articles main body content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return DateTime the date the news article was psoted
     */
    public function getPostDate(): DateTime
    {
        return $this->postDate;
    }

    /**
     * @return DateTime the date the news article was last edited, null if it was never edited
     */
    public function getLastEdited(): DateTime
    {
        return $this->lastEdited;
    }

    /**
     * @return string the news articles preamble
     */
    public function getPreamble(): string
    {
        return $this->preamble;
    }
}
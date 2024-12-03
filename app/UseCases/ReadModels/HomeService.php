<?php

namespace App\UseCases\ReadModels;

class HomeService
{
    /**
     * @var string
     */
    private $link = 'https://yandex.ru/maps-reviews-widget/59570882736?comments';

    public function getReviews(): array|string|null
    {
        try {

            $html = $this->getRequestResult($this->link);
            $item = $reviews = [];

            libxml_use_internal_errors(true);
            $doc = new \DOMDocument();
            $doc->loadHTML($html);
            $finder = new \DOMXPath($doc);

            $commentsNode = $finder->query("//div[@class='comment']");
            if ($commentsNode) {

                foreach ($commentsNode as $key => $commentNode) {

                    $rating    = 5;
                    $pathHalf  = $commentNode->getNodePath().'/div[2]/ul[1]/li[@_half]';
                    $pathEmpty = $commentNode->getNodePath().'/div[2]/ul[1]/li[@_empty]';

                    $ratingNodesHalf  = $finder->query($pathHalf);
                    $ratingNodesEmpty = $finder->query($pathEmpty);

                    if ($ratingNodesHalf->count() > 0) {
                        $rating = $rating - $ratingNodesHalf->count() * 0.5;
                    }
                    if ($ratingNodesEmpty->count() > 0) {
                        $rating = $rating - $ratingNodesEmpty->count();
                    }

                    $item["user_name"]    = $finder->query("//p[@class='comment__name']", $commentNode)[$key]->nodeValue;
                    $item["comment_text"] = $finder->query("//p[@class='comment__text']", $commentNode)[$key]->nodeValue;
                    $item["date"]         = $finder->query("//p[@class='comment__date']", $commentNode)[$key]->nodeValue;
                    $item["rating"]       = $rating;

                    $pathImage = $commentNode->getNodePath().'/div[1]/*[contains(@class, "comment__photo")]';
                    $item["user_icon"] = $finder->query($pathImage)[0]->nodeName == 'img' ? $finder->query($pathImage)[0]->getAttribute('src') : null;

                    $reviews[] = $item;
                }
                unset($html,$item);

                return [
                    'reviews' => $reviews,
                    'link'    => 'https://yandex.ru/maps/org/59570882736/reviews/'
                ];
            } else {
                return null;
            }

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    private function getRequestResult($request): bool|string
    {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return $server_output;
    }
}

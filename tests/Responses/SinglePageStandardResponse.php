<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 01.08.17
 * Time: 14:45
 */

namespace CampaigningBureau\UnbounceApiClient\Test\Responses;


use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use function GuzzleHttp\Psr7\stream_for;


class SinglePageStandardResponse implements GuzzleResponseMock, ResponseInterface
{

    public static $singlePagePublished = '{
                      "integrations_count" : 0,
                      "url" : "http://unbouncepages.com/forward-marketing-solutions/",
                      "sub_account_id" : "1552433",
                      "id" : "https://api.unbounce.com/raml/v0.4/examples/pages/page_get.json",
                      "name" : "Forward Marketing Solutions",
                      "variants_count" : 0,
                      "integrations_errors_count" : 0,
                      "domain" : "unbouncepages.com",
                      "metadata" : {
                        "documentation" : "https://api.unbounce.com:443/doc/pages",
                        "location" : "https://api.unbounce.com:443/pages/a0a3ffdf-5965-4ef2-8414-8afcded91f70",
                        "related" : {
                          "form_fields" : "https://api.unbounce.com:443/pages/a0a3ffdf-5965-4ef2-8414-8afcded91f70/form_fields",
                          "leads" : "https://api.unbounce.com:443/pages/a0a3ffdf-5965-4ef2-8414-8afcded91f70/leads",
                          "sub_account" : "https://api.unbounce.com:443/sub_accounts/1552433"
                        }
                      },
                      "state" : "published",
                      "tests" : {
                        "current" : {
                          "form_submits" : 2,
                          "visits" : 3,
                          "conversion_rate" : 33.33,
                          "clicks" : 0,
                          "id" : "a0a3ffdf-5965-4ef2-8414-8afcded91f70",
                          "conversions" : 1,
                          "losers" : "",
                          "has_results" : false,
                          "winner" : "",
                          "champion" : "a",
                          "visitors" : 3,
                          "conversion_rate_delta" : 0.0
                        }
                      },
                      "created_at" : "2015-12-16T00:59:29.000Z",
                      "last_published_at" : "2015-12-16T01:02:23.000Z",
                      "integrations" : [ ]
                    }';

    public function getBody(): Stream
    {
        return stream_for(SinglePageStandardResponse::$singlePagePublished);
    }

    public function getStatusCode(): int
    {
        return 200;
    }

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {

    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new protocol version.
     *
     * @param string $version HTTP protocol version
     *
     * @return static
     */
    public function withProtocolVersion($version)
    {

    }

    /**
     * Retrieves all message header values.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * While header names are not case-sensitive, getHeaders() will preserve the
     * exact case in which headers were originally specified.
     *
     * @return string[][] Returns an associative array of the message's headers. Each
     *     key MUST be a header name, and each value MUST be an array of strings
     *     for that header.
     */
    public function getHeaders()
    {

    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($name)
    {

    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     *
     * If the header does not appear in the message, this method MUST return an
     * empty array.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader($name)
    {

    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use getHeader() instead
     * and supply your own delimiter when concatenating.
     *
     * If the header does not appear in the message, this method MUST return
     * an empty string.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method MUST return an empty string.
     */
    public function getHeaderLine($name)
    {

    }

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * While header names are case-insensitive, the casing of the header will
     * be preserved by this function, and returned from getHeaders().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated header and value.
     *
     * @param string          $name  Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     *
     * @return static
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader($name, $value)
    {

    }

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new header and/or value.
     *
     * @param string          $name  Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     *
     * @return static
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withAddedHeader($name, $value)
    {

    }

    /**
     * Return an instance without the specified header.
     *
     * Header resolution MUST be done without case-sensitivity.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the named header.
     *
     * @param string $name Case-insensitive header field name to remove.
     *
     * @return static
     */
    public function withoutHeader($name)
    {

    }

    /**
     * Return an instance with the specified message body.
     *
     * The body MUST be a StreamInterface object.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return a new instance that has the
     * new body stream.
     *
     * @param StreamInterface $body Body.
     *
     * @return static
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body)
    {

    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @param int    $code         The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *                             provided status code; if none is provided, implementations MAY
     *                             use the defaults as suggested in the HTTP specification.
     *
     * @return static
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '')
    {

    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase()
    {

    }
}
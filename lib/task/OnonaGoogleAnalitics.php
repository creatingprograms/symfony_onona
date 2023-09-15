<?php
/**
 * Created by PhpStorm.
 * User: akorolev
 * Date: 05.03.2019
 * Time: 12:29
 */


namespace App;

use Google_Client;
use Google_Service_Analytics;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_OrderBy;
use Google_Service_AnalyticsReporting_ReportRequest;
use Google_Service_GetReportsResponse;
use Google_Service_Exception;

/**
 * https://github.com/googleapis/google-api-php-client
 *
 * onona1c@gmail.com Fjd67Se34wTRg&1
 *
 * Class Onona
 * @package App\Console\Commands\Googlan
 */
class OnonaGoogleAnalitics
{
    protected $analytics = null;

    const GOOGLE_ANALYTICS_MAX_RESULTS = 1000;

    public function __construct($key_file_location)
    {
        $KEY_FILE_LOCATION = $key_file_location;
        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("Analytics.v4.0");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_AnalyticsReporting($client);

        $this->analytics = $analytics;
    }


    /**
     * @param string $view_id like 'ga:83598476'
     * @param string $date_from yyyy-mm-dd or google date sintax
     * @param string $date_to yyyy-mm-dd or google date sintax
     * @param int $nextPageToken for pagination, not manual set
     * @return array
     */
    public function getStat($view_id, $date_from, $date_to, $nextPageToken = null)
    {

        $analytics = $this->analytics;

        // Replace with your view ID, for example XXXX.
        $VIEW_ID = $view_id;

        // Create the DateRange object.
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($date_from);
        $dateRange->setEndDate($date_to);


//&dimensions=,
//&sort=-ga:transactionId

        // Create the Metrics object.
        $transactionRevenueMetric = new Google_Service_AnalyticsReporting_Metric();
        $transactionRevenueMetric->setExpression("ga:transactionRevenue");

        $itemQuantityMetric = new Google_Service_AnalyticsReporting_Metric();
        $itemQuantityMetric->setExpression("ga:itemQuantity");


        //Dimension
        $sourceDimension = new Google_Service_AnalyticsReporting_Dimension();
        $sourceDimension->setName("ga:source");

		$mediumDimension = new Google_Service_AnalyticsReporting_Dimension();
        $mediumDimension->setName("ga:medium");

		//$sourceMediumDimension = new Google_Service_AnalyticsReporting_Dimension();
        //$sourceMediumDimension->setName("ga:sourceMedium");


        $regionDimension = new Google_Service_AnalyticsReporting_Dimension();
        $regionDimension->setName("ga:region");

        $transactionIdDimension = new Google_Service_AnalyticsReporting_Dimension();
        $transactionIdDimension->setName("ga:transactionId");


        $dimension1Dimension = new Google_Service_AnalyticsReporting_Dimension();
        $dimension1Dimension->setName("ga:dimension1");

        //Order
        $transactionIdOrdering = new Google_Service_AnalyticsReporting_OrderBy();
        $transactionIdOrdering->setFieldName("ga:transactionId");
        $transactionIdOrdering->setOrderType("VALUE");
        $transactionIdOrdering->setSortOrder("DESCENDING");


        // Create the ReportRequest object.
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges($dateRange);
        $request->setMetrics(array($transactionRevenueMetric,$itemQuantityMetric));
        $request->setDimensions(array($sourceDimension,$transactionIdDimension,$mediumDimension, $regionDimension));
        $request->setOrderBys(array($transactionIdOrdering));
        $request->setPageSize(1000);


        if ($nextPageToken) {//pagination
            $request->setPageToken($nextPageToken);
        }

        $reports = [];


        try {
            $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
            $body->setReportRequests(array($request));
            $reports = $analytics->reports->batchGet($body);

            $nextPageToken = $this->getNextPageToken($reports);
        } catch (Google_Service_Exception $E) {
            var_dump($E->getErrors());
        }

        //var_dump($nextPageToken);
        $result = $this->getResult($reports);

        $GOOGLE_ANALYTICS_MAX_RESULTS = OnonaGoogleAnalitics::GOOGLE_ANALYTICS_MAX_RESULTS;
        if (!$GOOGLE_ANALYTICS_MAX_RESULTS) {
            $GOOGLE_ANALYTICS_MAX_RESULTS = 1000;
        }
        if (count($result) == $GOOGLE_ANALYTICS_MAX_RESULTS) {
            $result = array_merge($result, $this->getStat($view_id, $date_from, $date_to,  $nextPageToken));
        }
        return $result;
    }

    /**
     * @param $reports
     * @return null|int
     */
    protected function getNextPageToken($reports)
    {
        if (count($reports) > 0) {
            return $reports[count($reports) - 1]->getNextPageToken();
        }
        return null;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getAliasDimension($name)
    {
        $result = '';
        switch ($name) {
          /*  case 'ga:date':
                $result = 'dater';
                break;
            case 'ga:source':
                $result = 'domen';
                break;
            case 'ga:medium':
                $result = 'medium';
                break;
            case 'ga:keyword':
                $result = 'keyword';
                break;
            case 'ga:sessions':
                $result = 'visits';
                break;*/

            default:
                $result = $name;
        }
        return $result;
    }

    /**
     * @param mixed $reports Google_Service_GetReportsResponse
     * @return array
     */
    protected function getResult($reports)
    {
        $result = [];

        for ($reportIndex = 0; $reportIndex < count($reports); $reportIndex++) {
            $report = $reports[$reportIndex];
            $header = $report->getColumnHeader();
            $dimensionHeaders = $header->getDimensions();
            $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
            $rows = $report->getData()->getRows();

            for ($rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                $_res = [];
                $row = $rows[$rowIndex];
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();
                for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
                    //print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
                    $_res[$this->getAliasDimension($dimensionHeaders[$i])] = $dimensions[$i];
                }
                $values = $metrics[0]->getValues();
                for ($j = 0; $j < count($metricHeaders); $j++) {
                    $entry = $metricHeaders[$j];
                    // print($entry->getType() .' '.$entry->getName() . ": " . $values[$j] . "\n");
                    switch ($entry->getType()) {
                        case 'INTEGER':
                        case 'TIME':
                            $_res[$entry->getName()] = intval($values[$j]);
                            break;
                        default:
                            $_res[$entry->getName()] = $values[$j];
                    }

                }
                //print("\n");
                $result[] = $_res;
            }
        }
        return $result;
    }
}

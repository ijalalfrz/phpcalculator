<?php
// namespace Tests;

// use Tests\BaseTest;

// final class HistoryControllerTest extends BaseTest
// {
//     public function testCorrectCall()
//     {
//         $body = json_encode(
//           [
//             'field_one' => 'some data',
//             'field_two' => [
//               'apple',
//               'banana',
//             ],
//           ]
//         );

//         $response = $this->dispatch(null, '/calculator', 'GET');

//         $this->assertSame($response->getStatusCode(), 200);
//         $parsedResponse = json_decode($response->getBody(), true);
//         $this->assertArraySubset(['message' => 'success'], $parsedResponse);
//     }
// }

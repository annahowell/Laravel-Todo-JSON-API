<?php

namespace Tests\Feature\JsonApi;

class DataProvider
{
    public function tagPostPutInputValidation(): array
    {
        $sixtyFiveChars = '12345678901234567890123456789012345678901234567890123456789012345';

        return [
            'title: \'\' & color: \'\'' => ['', ''],
            'title: f & color: #ff00ff' => ['f', '#ff00ff'],
            'title: ' . $sixtyFiveChars . ' (65 chars) & color: #ff00ff' => [$sixtyFiveChars, '#ff00ff'], //65
            'title: foo & color: ff00ff' => ['foo', 'ff00ff'],
            'title: foo & color: #ff00f' => ['foo', '#ff00f'],
        ];
    }


    public function taskPostPutInputValidation(): array
    {
        return [
            'body: \'\' & tags: \'1\''   => ['', '1', 422],
            'body: \'\' & tagsaa: \'1\'' => ['valid body', '9999', 404],
        ];
    }
}

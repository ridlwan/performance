<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $GSMART = [
            55,
            77,
            78,
            234,
            235,
            271,
            334,
            370,
            371,
            372,
            373,
            380,
            418,
            75,
            208,
            233,
            365,
            425,
            80,
            185,
            186,
            240,
            248,
            253,
            299,
            301,
            312,
            322,
            335,
            346,
            369,
            389,
            406,
            73,
            187,
            251,
            289,
            331,
            345,
            351,
            377,
            387,
            390,
            414,
            424,
            430
        ];

        $GSMARTActivities = Activity::whereIn('id', $GSMART)->get();
        foreach ($GSMARTActivities as $GSMARTActivity) {
            $GSMARTActivity->project_id = 2;
            $GSMARTActivity->save();
        }
        
        $SPDocument = [
            1,
            34,
            617,
            626,
            666,
            674,
            2,
            5,
            19,
            42,
            122,
            496,
            502,
            508,
            528,
            560,
            623,
            682,
            16,
            24,
            25,
            43,
            44,
            64,
            68,
            88,
            93,
            95,
            105,
            116,
            120,
            121,
            124,
            125,
            126,
            127,
            128,
            129,
            130,
            131,
            132,
            133,
            134,
            154,
            158,
            164,
            166,
            167,
            214,
            232,
            239,
            277,
            375,
            410,
            442,
            460,
            465,
            484,
            512,
            522,
            562,
            587,
            599,
            631,
            635,
            644,
            647,
            662,
            680,
            681,
            687
        ];

        $SPDocumentActivities = Activity::whereIn('id', $SPDocument)->get();
        foreach ($SPDocumentActivities as $SPDocumentActivity) {
            $SPDocumentActivity->project_id = 3;
            $SPDocumentActivity->save();
        }

        $GSE = [
            22,
            52,
            97,
            141,
            220,
            431,
            434,
            438,
            471,
            511,
            529,
            543,
            557,
            582,
            18,
            51,
            92,
            213,
            229,
            255,
            287,
            329,
            366,
            386,
            391,
            395,
            426,
            437,
            461,
            480,
            501,
            530,
            531,
            533,
            534,
            544,
            574,
            576,
            577,
            579,
            607,
            624,
            645,
            656,
            667,
            678
        ];

        $GSEActivities = Activity::whereIn('id', $GSE)->get();
        foreach ($GSEActivities as $GSEActivity) {
            $GSEActivity->project_id = 4;
            $GSEActivity->save();
        }
        
        $Kitting = [
            72,
            157,
            189,
            231,
            245,
            266,
            281,
            294,
            315,
            344,
            361,
            376,
            188,
            482,
            636,
            650,
            665,
            8,
            20,
            56,
            74,
            135,
            207,
            216,
            217,
            423,
            447,
            448,
            473,
            486,
            515,
            532,
            566,
            572,
            580,
            581,
            601,
            604,
            618,
            641,
            646,
            660,
            677,
            679,
            689,
            202,
            221,
            302,
            385,
            422,
            538,
            578,
            583,
            606,
            634,
            663,
            192
        ];

        $KittingActivities = Activity::whereIn('id', $Kitting)->get();
        foreach ($KittingActivities as $KittingActivity) {
            $KittingActivity->project_id = 5;
            $KittingActivity->save();
        }
        
        $IERAPPS = [
            32,
            37,
            109,
            110,
            205,
            223,
            246,
            261,
            270,
            275,
            284,
            285,
            307,
            308,
            313,
            316,
            325,
            35,
            38,
            288,
            500,
            537,
            608,
            15,
            41,
            147,
            165,
            184,
            196,
            243,
            282,
            333,
            350,
            393,
            467,
            513,
            556,
        ];

        $IERAPPSActivities = Activity::whereIn('id', $IERAPPS)->get();
        foreach ($IERAPPSActivities as $IERAPPSActivity) {
            $IERAPPSActivity->project_id = 6;
            $IERAPPSActivity->save();
        }
        
        $EngineStand = [
            104,
            227,
            247,
            250,
            272,
            283,
            310,
            311,
            337,
            356,
            383,
            397,
            421,
            439,
            441,
            458,
            463,
            469,
            488,
            491,
            497,
            499,
            503,
            517,
            518,
            519,
            521,
            527,
            535,
            567,
            569,
            591,
            592,
            609,
            611,
            615,
            616,
            619,
            620,
            621,
            622,
            627,
            630,
            640,
            642,
            652,
            653,
            654,
            657,
            658,
            659,
            520,
            523,
            526,
            219,
            309,
            584,
            3,
            6,
            7,
            26,
            27,
            33,
            58,
            67,
            101,
            111,
            148,
            149,
            153,
            155,
            198,
            199,
            224,
            228,
            230,
            244,
            274,
            292,
            317,
            319,
            324,
            340,
            358,
            415,
            450,
            452,
            481,
            483,
            509,
            548,
            585,
            590,
            614,
            629,
            637,
            655,
            664,
            676,
            688
        ];

        $EngineStandActivities = Activity::whereIn('id', $EngineStand)->get();
        foreach ($EngineStandActivities as $EngineStandActivity) {
            $EngineStandActivity->project_id = 7;
            $EngineStandActivity->save();
        }
        
        $PMO = [
            9,
            10,
        ];

        $PMOActivities = Activity::whereIn('id', $PMO)->get();
        foreach ($PMOActivities as $PMOActivity) {
            $PMOActivity->project_id = 8;
            $PMOActivity->save();
        }
        
        $Sticker = [
            23,
            40,
            57,
            59,
            79,
            139,
            140,
            151,
            152,
            156,
            176,
            177,
            181,
            225,
            226,
            403,
            420,
            568,
            683,
            419
        ];

        $StickerActivities = Activity::whereIn('id', $Sticker)->get();
        foreach ($StickerActivities as $StickerActivity) {
            $StickerActivity->project_id = 9;
            $StickerActivity->save();
        }

        $PLB = [
            589,
            605,
            588
        ];

        $PLBActivities = Activity::whereIn('id', $PLB)->get();
        foreach ($PLBActivities as $PLBActivity) {
            $PLBActivity->project_id = 10;
            $PLBActivity->save();
        }
    }
}

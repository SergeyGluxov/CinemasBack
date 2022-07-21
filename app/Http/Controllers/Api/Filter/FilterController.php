<?php

namespace App\Http\Controllers\Api\Filter;


use App\FreeDay;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Repositories\ContentCreatorRepository;
use App\Http\Repositories\ContentGenreRepository;
use App\Http\Repositories\ContentRepository;
use App\Http\Repositories\CountryRepository;
use App\Http\Repositories\CreatorRepository;
use App\Http\Repositories\GenreRepository;
use App\Http\Repositories\TypeContentRepository;
use App\Http\Resources\ContentCollection;
use App\Http\Resources\ContentShortResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\FilterCollection;
use App\Http\Resources\ReleaseResource;
use App\Models\Content;
use App\Models\Country;
use App\Models\YearsFilter;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    protected $creatorRepository;
    protected $contentRepository;
    protected $genreRepository;
    protected $countryRepository;
    protected $typeContentRepository;
    protected $contentGenreRepository;
    protected $contentCreatorRepository;

    public function __construct(CreatorRepository $creatorRepository,
                                ContentRepository $contentRepository,
                                GenreRepository $genreRepository,
                                TypeContentRepository $typeContentRepository,
                                ContentGenreRepository $contentGenreRepository,
                                ContentCreatorRepository $contentCreatorRepository,
                                CountryRepository $countryRepository
    )
    {
        $this->creatorRepository = $creatorRepository;
        $this->contentRepository = $contentRepository;
        $this->genreRepository = $genreRepository;
        $this->typeContentRepository = $typeContentRepository;
        $this->contentGenreRepository = $contentGenreRepository;
        $this->contentCreatorRepository = $contentCreatorRepository;
        $this->countryRepository = $countryRepository;
    }


    public function getFilters()
    {
        $arrayYears = array(
            new YearsFilter(1999, "1999"),
            new YearsFilter(2000, "2000"),
            new YearsFilter(2001, "2001"),
            new YearsFilter(2002, "2002"),
            new YearsFilter(2003, "2003"),
            new YearsFilter(2004, "2004"),
            new YearsFilter(2005, "2005"),
            new YearsFilter(2006, "2006"),
            new YearsFilter(2007, "2007"),
            new YearsFilter(2008, "2008"),
            new YearsFilter(2009, "2009"),
            new YearsFilter(2010, "2010"),
            new YearsFilter(2011, "2011"),
            new YearsFilter(2012, "2012"),
            new YearsFilter(2013, "2013"),
            new YearsFilter(2014, "2014"),
            new YearsFilter(2015, "2015"),
            new YearsFilter(2016, "2016"),
            new YearsFilter(2017, "2017"),
            new YearsFilter(2018, "2018"),
            new YearsFilter(2020, "2020"),
            new YearsFilter(2021, "2021"),
            new YearsFilter(2022, "2022"),
            new YearsFilter(2023, "2023"));

        $array = [
            [
                "title" => 'Страны',
                "type" => 'countries',
                "items" => $this->countryRepository->take(5),
            ],
            [
                "title" => 'Жанры',
                "type" => 'genres',
                "items" => $this->genreRepository->all(),
            ],
            [
                "title" => 'Года',
                "type" => 'years',
                "items" => $arrayYears,
            ]
        ];
        return json_encode($array, true);

        $array = [
            "countries" => $this->countryRepository->take(5),
            "genres" => $this->genreRepository->all(),
        ];
        return new FilterCollection($array);
    }

}

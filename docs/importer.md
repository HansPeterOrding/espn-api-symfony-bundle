# Importer

This section describes the usage of the out of the box importers that help to keep your local data up to date.

The general idea is to work with different entry points depending on the usecase you are handling. The entry points implemented yet are:

* Season
* Teams
* Week

The different entry points are described in more detail in the following paragraphs.

## Entry point season

This entry point is designed to import season base data. This includes:

* The season itself
* Current season type
* Season types
* Season type groups
* Season type group hierarchy
* Season type weeks
* If already imported: Assignment of teams to groups

You can use HansPeterOrding\EspnApiSymfonyBundle\Service\SeasonImportService to import the mentioned parts. The method importEspnLeague() only expects the year to be imported as parameter. You can also pass an array of object strings that should be included in the import:

```
$seasonImportService = new SeasonImportService(...);
$events = $seasonImportService->importEspnLeague(2026, [
    self::IMPORT_ENTITY_TYPE => true,
    self::IMPORT_ENTITY_TYPES => true,
    self::IMPORT_ENTITY_TYPE_GROUPS => true,
    self::IMPORT_ENTITY_TYPE_WEEKS => true,
    self::IMPORT_ENTITY_RANKINGS => false,
    self::IMPORT_ENTITY_FUTURES => false,
]);
```

If you leave out some or all of those constants, the importer simply skips those parts.

## Entry point teams

## Entry point week

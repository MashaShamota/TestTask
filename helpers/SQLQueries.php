<?php

class SQLQueries
{
    public static function getSelectTopCountriesQuery($count)
    {
        return '
      SELECT
        country,
        sum(hints) as total
      FROM `actions`
      GROUP BY country
      ORDER BY total DESC
      LIMIT ' . $count;
    }

    public static function getSelectStatistics(array $countries)
    {
        array_walk($countries, function (&$val) {
            $val = "'" . $val . "'";
        });
        return '
      SELECT
          country,
          action,
          sum(hints) as total
        FROM `actions`
        WHERE country IN (' . implode(',', $countries) . ')
        AND date >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY
        GROUP BY country,action';
    }

    public static function getUpdateCounterQuery()
    {
        return '
      UPDATE actions
      SET hints = hints + 1
      WHERE action = :event
      AND country = :country
      AND DATE(`date`) = :date';
    }

    public static function getInsertEventQuery()
    {
        return '
      INSERT INTO actions
      (action, country, date, hints)
      VALUES
      (:event, :country, :date, :hints)';
    }
}

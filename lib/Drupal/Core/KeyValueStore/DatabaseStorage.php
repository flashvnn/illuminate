<?php

/**
 * @file
 * Contains Drupal\Core\KeyValueStore\DatabaseStorage.
 */

namespace Drupal\Core\KeyValueStore;

use Drupal\Component\Serialization\SerializationInterface;
use Drupal\Core\Database\Query\Merge;
use Illuminate\Database\Connection;

/**
 * Defines a default key/value store implementation.
 *
 * This is Drupal's default key/value store implementation. It uses the database
 * to store key/value data.
 */
class DatabaseStorage extends StorageBase {

  /**
   * The serialization class to use.
   *
   * @var \Drupal\Component\Serialization\SerializationInterface
   */
  protected $serializer;

  /**
   * The database connection.
   *
   * @var \Illuminate\Database\Connection
   */
  protected $connection;

  /**
   * The name of the SQL table to use.
   *
   * @var string
   */
  protected $table;

  /**
   * Overrides Drupal\Core\KeyValueStore\StorageBase::__construct().
   *
   * @param string $collection
   *   The name of the collection holding key and value pairs.
   * @param \Drupal\Component\Serialization\SerializationInterface $serializer
   *   The serialization class to use.
   * @param \Illuminate\Database\Connection $connection
   *   The database connection to use.
   * @param string $table
   *   The name of the SQL table to use, defaults to key_value.
   */
  public function __construct($collection, SerializationInterface $serializer, Connection $connection, $table = 'key_value') {
    parent::__construct($collection);
    $this->serializer = $serializer;
    $this->connection = $connection;
    $this->table = $table;
  }

  /**
   * {@inheritdoc}
   */
  public function has($key) {
/*
  return (bool) $this->connection->query('SELECT 1 FROM {' . $this->connection->escapeTable($this->table) . '} WHERE collection = :collection AND name = :key', array(
        ':collection' => $this->collection,
        ':key' => $key,
      ))->fetchField();

*/
    return (bool) $this->table()
                         ->where('collection', '=', $this->collection)
                         ->where('name', '=', $key)
                         ->first();
  }

  /**
   * Implements Drupal\Core\KeyValueStore\KeyValueStoreInterface::getMultiple().
   */
  public function getMultiple(array $keys) {
/*    $values = array();
    try {
      $result = $this->connection->query('SELECT name, value FROM {' . $this->connection->escapeTable($this->table) . '} WHERE name IN (:keys) AND collection = :collection', array(':keys' => $keys, ':collection' => $this->collection))->fetchAllAssoc('name');
      foreach ($keys as $key) {
        if (isset($result[$key])) {
          $values[$key] = $this->serializer->decode($result[$key]->value);
        }
      }
    }
    catch (\Exception $e) {
      // @todo: Perhaps if the database is never going to be available,
      // key/value requests should return FALSE in order to allow exception
      // handling to occur but for now, keep it an array, always.
    }
    return $values;*/
    $values = array();
    try {
      $result = $this->table()
              ->where('collection', '=', $this->collection)
              ->whereIn('name', $keys)
              ->lists('value', 'name');

      foreach ($keys as $key) {
        if (isset($result[$key])) {
          $values[$key] = $this->serializer->decode($result[$key]);
        }
      }
    } catch (\Exception $e) {
      // @todo: Perhaps if the database is never going to be available,
      // key/value requests should return FALSE in order to allow exception
      // handling to occur but for now, keep it an array, always.
    }

    return $values;
  }

  /**
   * Implements Drupal\Core\KeyValueStore\KeyValueStoreInterface::getAll().
   */
  public function getAll() {
/*
$result = $this->connection->query('SELECT name, value FROM {' . $this->connection->escapeTable($this->table) . '} WHERE collection = :collection', array(':collection' => $this->collection));
*/
    $values = array();
    $result = $this->table()->where('collection', '=', $this->collection)->get();

    foreach ($result as $item) {
      if ($item) {
        $values[$item->name] = $this->serializer->decode($item->value);
      }
    }
    return $values;
  }

  /**
   * Implements Drupal\Core\KeyValueStore\KeyValueStoreInterface::set().
   */
  public function set($key, $value) {
/*    $this->connection->merge($this->table)
      ->keys(array(
        'name' => $key,
        'collection' => $this->collection,
      ))
      ->fields(array('value' => $this->serializer->encode($value)))
      ->execute();*/
    try
    {
      $this->table()->insert(array(
        'name' => $key,
        'collection' => $this->collection,
        'value' => $this->serializer->encode($value),
      ));
    }
    catch (\Exception $e)
    {
      $this->table()->where('collection', '=', $this->collection)->where('name', '=', $key)->update(
        array('value' => $this->serializer->encode($value))
      );
    }
  }

  /**
   * Implements Drupal\Core\KeyValueStore\KeyValueStoreInterface::setIfNotExists().
   */
  public function setIfNotExists($key, $value) {
/*    $result = $this->connection->merge($this->table)
      ->insertFields(array(
        'collection' => $this->collection,
        'name' => $key,
        'value' => $this->serializer->encode($value),
      ))
      ->condition('collection', $this->collection)
      ->condition('name', $key)
      ->execute();
    return $result == Merge::STATUS_INSERT;*/
    try
    {
      $this->table()->insert(array(
        'name' => $key,
        'collection' => $this->collection,
        'value' => $this->serializer->encode($value),
      ));

      return TRUE;
    }
    catch (\Exception $e)
    {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function rename($key, $new_key) {
/*    $this->connection->update($this->table)
      ->fields(array('name' => $new_key))
      ->condition('collection', $this->collection)
      ->condition('name', $key)
      ->execute();*/
      $this->table()->where('collection', '=', $this->collection)
                    ->where('name', $key)
                    ->update(array('name' => $new_key));
  }

  /**
   * Implements Drupal\Core\KeyValueStore\KeyValueStoreInterface::deleteMultiple().
   */
  public function deleteMultiple(array $keys) {
    // Delete in chunks when a large array is passed.
/*    do {
      $this->connection->delete($this->table)
        ->condition('name', array_splice($keys, 0, 1000))
        ->condition('collection', $this->collection)
        ->execute();
    }
    while (count($keys));*/
    do {
    $this->table()
                ->where('collection', '=', $this->collection)
                ->whereIn('name', array_splice($keys, 0, 1000))
                ->delete();
    }
    while (count($keys));
  }

  /**
   * Implements Drupal\Core\KeyValueStore\KeyValueStoreInterface::deleteAll().
   */
  public function deleteAll() {
/*    $this->connection->delete($this->table)
      ->condition('collection', $this->collection)
      ->execute();*/
      $this->table()->where('collection', '=', $this->collection)->delete();
  }

  /**
   * Get a query builder for the cache table.
   *
   * @return \Illuminate\Database\Query\Builder
   */
  protected function table()
  {
    return $this->connection->table($this->table);
  }

  /**
   * Get the underlying database connection.
   *
   * @return \Illuminate\Database\Connection
   */
  public function getConnection()
  {
    return $this->connection;
  }
}

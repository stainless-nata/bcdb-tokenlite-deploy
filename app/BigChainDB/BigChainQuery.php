<?php
/**
 * BigChain Collection : 
 *
 */
namespace App\BigChainDB;

use DateTime;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BigChainQuery
{
    protected static $driver = null;
    protected $table;
    protected $object;
    protected $queries = null;
    protected $orders = [];
    protected $limit = null;
    protected $page = null;
    
    public function __construct(string $table = null, string $object = null)
    {
        $this->table = $table;
        $this->object = $object;
        if(!self::$driver) {
            self::$driver = new Client([
                'base_uri' => config('bigchaindb.driver'),
                'headers' => config('bigchaindb.headers')
            ]);
        }
    }

    private function getParams() {
        return [
            'object' => $this->table,
            'where' => $this->queries ? json_encode($this->queries) : null,
            'orderBy' => $this->orders,
            'page' => $this->page,
            'limit' => $this->limit
        ];
    }

    public function paginate() {
        return new BigChainPaginator($this);
    }

    public function get()
    {    
        Log::info('GET ' . $this->table . ' ' . json_encode($this->getParams()));

        $response = self::$driver->get('/', [ 'query' => $this->getParams() ]);

        $result = json_decode($response->getBody()->getContents());
        
        if(!is_array($result->data)) {
            throw new \Exception(json_encode($result));
        }

        $items = new Collection(array_map(function($item) { return new $this->object($item); }, $result->data));

        $res =  isset($result->total) ? [
            'items' => $items,
            'total' => $result->total
        ] : $items;

        Log::info('RETURN ' . json_encode($res));

        return $res;
    }
    
    public function all()
    {
        return $this->get();
    }

    public function first()
    {
        return $this->limit(1)->get()->first();
    }

    public function value(string $column) 
    {
        $res = $this->first();
        return $res ? $res->{$column} : null;
    }

    public function count()
    {
        $res = self::$driver->get('/count', [ 'query' => $this->getParams() ]);
        return json_decode($res->getBody()->getContents())->data;
    }

    public function sum($column)
    {
        $params = $this->getParams();
        $params['column'] = $column;
        $res = self::$driver->get('/sum', [ 'query' => $params ]);
        return json_decode($res->getBody()->getContents())->data;
    }

    public function create($data)
    {
        $res = self::$driver->post('/', [ 'json' => [
            'data' => $data,
            'object' => $this->table
        ]]);
        $sss = $this->where($data)->first();
        Log::info("CREATE " . json_encode($sss));
        return $sss;
    }

    public function insert($data)
    {
        return $this->create($data);
    }

    public function update($data)
    {
        $params = $this->getParams();
        $params['data'] = $data;
        $res = self::$driver->put('/', [ 'json' => $params ]);
        Log::info("Update" . json_encode(json_decode($res->getBody()->getContents())));
    }

    public function delete()
    {
        $res = self::$driver->delete('/', [ 'query' => $this->getParams() ]);
        Log::info("Delete" . json_encode(json_decode($res->getBody()->getContents())));
    }

    /**
     * Add OFFSET & LIMIT clause to the query.
     * @param  int   $page
     * @param  int   $pageSize
     * @return Collection
     */
    public function pagination($page, $pageSize)
    {
        $this->page = $page;
        $this->limit = $pageSize;
        $res =  $this->get();
        Log::info("PAGE " . json_encode($res));
        return $res;
    }

    /**
     * Add LIMIT clause to the query.
     * @param  int   $count
     * @return $this
     */
    public function limit($count)
    {
        $this->limit = $count;
        return $this;
    }

    /**
     * Add ORDER BY clause to the query.
     *
     * @param  string  $column
     * @param  string  $order
     * @return $this
     */
    public function orderBy($column, $order)
    {
        $this->orders[] = [
            'key' => $column,
            'order' => $order
        ];
        return $this;
    }

    public function latest()
    {
        $this->orders[] = [ 'created_at' => 'DESC' ];
        return $this;
    }

    /**
     * Add WHERE clause to the query.
     *
     * @param  string  $connector
     * @param  string|array|\Closure  $column
     * @param  mixed   $operator
     * @param  mixed   $value
     * @return $this
     */
    private function addQuery($connector, $column, $operator, $value)
    {
        // Build Query
        if($value) {
            $query = [
                'operator' => strtolower($operator),
                'operand' => [
                    $column => $value
                ]
            ];
        } else if($operator) {
            $query = [
                'operator' => '==',
                'operand' => [
                    $column => $operator
                ]
            ];
        } else if($column instanceof \Closure) {
            throw new \Exception('invalid second parameter as a function');
        } else {
            $query = [
                'operator' => '==',
                'operand' => $column
            ];
        }
        // Combine Query
        if(!$this->queries) {
            $this->queries = $query;
        } else if(($this->queries['connector'] ?? null) === $connector) {
            $this->queries['queries'][] = $query;
        } else {
            $this->queries = [
                'connector' => $connector,
                'queries' => [
                    $this->queries,
                    $query
                ]
            ];
        }
        // Return Object
        return $this;
    }

    public function where($column, $operator = null, $value = null)
    {
        return $this->addQuery('and', $column, $operator, $value);
    }

    public function whereIn($column, $value) {
        return $this->where($column, 'in', $value);
    }

    public function whereNotIn($column, $value) {
        return $this->where($column, '!in', $value);
    }

    public function whereNotNull($column) {
        return $this->where($column, '!=', null);
    }

    public function whereBetween($column, $value) {
        return $this->where($column, 'between', $value);
    }

    public function whereDate($column, $value) {

        return $this->whereBetween($column, [
            (new DateTime($value . ' 00:00:00'))->getTimestamp(),
            (new DateTime($value . ' 23:59:59'))->getTimestamp(),
        ]);
    }

    public function orWhere($column, $operator = null, $value = null)
    {
        return $this->addQuery('or', $column, $operator, $value);
    }
    
    public function orWhereIn($column, $value) {
        return $this->orWhere($column, 'in', $value);
    }

    public function orWhereNotIn($column, $value) {
        return $this->orWhere($column, '!in', $value);
    }

    public function orWhereBetween($column, $value) {
        return $this->orWhere($column, 'between', $value);
    }
}
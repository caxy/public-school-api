<?php

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SchoolController.
 */
class SchoolController
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * SchoolController constructor.
     *
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function getAction($id)
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $query = $queryBuilder
          ->select('*')
          ->from('schools')
          ->where('NCESSCH = ?')
          ->setParameters([$id])
        ;

        $stmt = $query->execute();
        $school = $stmt->fetch();

        return new JsonResponse($school);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function cgetAction(Request $request)
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $params = $request->query->all();

        $query = $queryBuilder
          ->select('*')
          ->from('schools')
          ->where(call_user_func_array([$queryBuilder->expr(), 'andX'], array_map(function ($param) use ($queryBuilder) {
                return $queryBuilder->expr()->like($param, '?');
            }, array_keys($params))
          ))
          ->setParameters(array_map(function ($param) {
              return '%'.$param.'%';
          }, array_values($params)))
        ;

        $stmt = $query->execute();
        $schools = $stmt->fetchAll();

        return new JsonResponse($schools);
    }
}

<?php

namespace backend\controllers;

use Yii;
use backend\models\Customer;
use backend\models\CustomerSearch;
use backend\models\ContactSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchCustomerModel = new CustomerSearch();
        $dataProviderCustomer = $searchCustomerModel->search(Yii::$app->request->queryParams);
        $dataProviderCustomer->query->andWhere(['user_id' => Yii::$app->user->id]);

        $searchContactModel = new ContactSearch();
        $dataProviderContact = $searchContactModel->search(Yii::$app->request->queryParams);
        $dataProviderContact->query->andWhere(['user_id' => Yii::$app->user->id]);

        return $this->render('index', [
            'searchCustomerModel' => $searchCustomerModel,
            'dataProviderCustomer' => $dataProviderCustomer,
            'searchContactModel' => $searchContactModel,
            'dataProviderContact' => $dataProviderContact,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $customer_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionView($customer_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($customer_id, Yii::$app->user->id),
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->id;
            $model->save();

            return $this->redirect(['view', 'customer_id' => $model->customer_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $customer_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionUpdate($customer_id)
    {
        $model = $this->findModel($customer_id, Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'customer_id' => $model->customer_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $customer_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionDelete($customer_id)
    {
        $this->findModel($customer_id, Yii::$app->user->id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $customer_id
     * @param integer $user_id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($customer_id, $user_id)
    {
        if (($model = Customer::findOne(['customer_id' => $customer_id, 'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php
class CategoryController
{
    public function categoryList()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?act=login");
            exit;
        }

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAllCategories();

        require_file_view("Admin/Quanlydanhmuc/Catergorylist", compact("categories"));
    }

    public function addCategory()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?act=login");
            exit;
        }

        require_file_view("Admin/Quanlydanhmuc/AddCategory");
    }

    public function saveCategory()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?act=login");
            exit;
        }

        $category_name = $_POST['category_name'] ?? null;

        if (!$category_name) {
            header("Location: ?act=category-list&msg=error");
            exit;
        }

        $categoryModel = new CategoryModel();
        $result = $categoryModel->addCategory($category_name);

        if ($result) {
            header("Location: ?act=category-list&msg=success");
        } else {
            header("Location: ?act=category-list&msg=exists");
        }
        exit;
    }

    public function editCategory()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?act=login");
            exit;
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?act=category-list");
            exit;
        }

        $categoryModel = new CategoryModel();
        $category = $categoryModel->getCategoryById($id);

        if (!$category) {
            header("Location: ?act=category-list");
            exit;
        }

        require_file_view("Admin/Quanlydanhmuc/EditCategory", compact("category"));
    }

    public function updateCategory()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?act=login");
            exit;
        }

        $id = $_POST['id'] ?? null;
        $category_name = $_POST['category_name'] ?? null;

        if (!$id || !$category_name) {
            header("Location: ?act=category-list&msg=error");
            exit;
        }

        $categoryModel = new CategoryModel();
        $result = $categoryModel->updateCategory($id, $category_name);

        if ($result) {
            header("Location: ?act=category-list&msg=updated");
        } else {
            header("Location: ?act=category-list&msg=error");
        }
        exit;
    }

    public function deleteCategory()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?act=login");
            exit;
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?act=category-list");
            exit;
        }

        $categoryModel = new CategoryModel();
        $result = $categoryModel->deleteCategory($id);

        if ($result) {
            header("Location: ?act=category-list&msg=deleted");
        } else {
            header("Location: ?act=category-list&msg=error");
        }
        exit;
    }
}
?>

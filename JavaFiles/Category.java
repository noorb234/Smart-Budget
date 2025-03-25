package JavaFiles;
class Category{
    public Integer categoryID;
    private String name;
    private Float budgetLimit;
    public Integer UserID;

    public Category() {
        categoryID = null;
        name = null;
        budgetLimit = null;
        UserID = null;
    }
    public Category(Integer categoryID, String name, Float budgetLimit, Integer UserID) {
        this.categoryID = categoryID;
        this.name = name;
        this.budgetLimit = budgetLimit;
        this.UserID = UserID;
    }

    public Integer getCategoryID() {
        return categoryID;
    }

    public void setCategoryID(Integer categoryID) {
        this.categoryID = categoryID;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public Float getBudgetLimit() {
        return budgetLimit;
    }

    public void setBudgetLimit(Float budgetLimit) {
        this.budgetLimit = budgetLimit;
    }

    public Integer getUserID() {
        return UserID;
    }

    public void setUserID(Integer userID) {
        UserID = userID;
    }

    @Override
    public String toString() {
        return "Category{" +
                "categoryID=" + categoryID +
                ", name='" + name + '\'' +
                ", budgetLimit=" + budgetLimit +
                ", UserID=" + UserID +
                '}';
    }
}
package JavaFiles;
import java.util.Arrays;

class Budget{
    public Integer budgetID;
    private Float monthlyLimit;
    private Category[] category;
    public Integer userID;
    public Budget() {
        budgetID = null;
        monthlyLimit = null;
        category = null;
        userID = null;

    }

    //set a Budget() ???
    public Budget(Integer budgetID, Float monthlyLimit, Category[] category, Integer userID) {
        this.budgetID = budgetID;
        this.monthlyLimit = monthlyLimit;
        this.category = category;
        this.userID = userID;
    }

    public Integer getBudgetID() {
        return budgetID;
    }

    public void setBudgetID(Integer budgetID) {
        this.budgetID = budgetID;
    }

    public Float getMonthlyLimit() {
        return monthlyLimit;
    }

    public void setMonthlyLimit(Float monthlyLimit) {
        this.monthlyLimit = monthlyLimit;
    }

    public Category[] getCategory() {
        return category;
    }

    public void setCategory(Category[] category) {
        this.category = category;
    }

    public Integer getUserID() {
        return userID;
    }

    public void setUserID(Integer userID) {
        this.userID = userID;
    }

    public Budget adjustBudget(Budget budget) {
        return budget;
    }

    @Override
    public String toString() {
        return "Budget{" +
                "budgetID=" + budgetID +
                ", monthlyLimit=" + monthlyLimit +
                ", category=" + Arrays.toString(category) +
                ", userID=" + userID +
                '}';
    }

}
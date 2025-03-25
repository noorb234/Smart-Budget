package JavaFiles;
class Goal{
    public Integer goalID;
    private String goalName;
    private Float targetAmount;
    private Float currentAmount;
    private String deadLine;
    public Integer categoryID;

    public Goal() {
        goalID = null;
        goalName = null;
        targetAmount = null;
        currentAmount = null;
        deadLine = null;
        categoryID = null;
    }

    public Goal(Integer goalID, String goalName, Float targetAmount, Float currentAmount, String deadLine, Integer categoryID) {
        this.goalID = goalID;
        this.goalName = goalName;
        this.targetAmount = targetAmount;
        this.currentAmount = currentAmount;
        this.deadLine = deadLine;
        this.categoryID = categoryID;
    }

    public Integer getGoalID() {
        return goalID;
    }

    public void setGoalID(Integer goalID) {
        this.goalID = goalID;
    }

    public String getGoalName() {
        return goalName;
    }

    public void setGoalName(String goalName) {
        this.goalName = goalName;
    }

    public Float getTargetAmount() {
        return targetAmount;
    }

    public void setTargetAmount(Float targetAmount) {
        this.targetAmount = targetAmount;
    }

    public Float getCurrentAmount() {
        return currentAmount;
    }

    public void setCurrentAmount(Float currentAmount) {
        this.currentAmount = currentAmount;
    }

    public String getDeadLine() {
        return deadLine;
    }

    public void setDeadLine(String deadLine) {
        this.deadLine = deadLine;
    }

    public Integer getCategoryID() {
        return categoryID;
    }

    public void setCategoryID(Integer categoryID) {
        this.categoryID = categoryID;
    }

    @Override
    public String toString() {
        return "Goal{" +
                "goalID=" + goalID +
                ", goalName='" + goalName + '\'' +
                ", targetAmount=" + targetAmount +
                ", currentAmount=" + currentAmount +
                ", deadLine='" + deadLine + '\'' +
                ", categoryID=" + categoryID +
                '}';
    }
    public void addGoal(Goal goal) {
    }
    public void removeGoal(Goal goal) {

    }
    public Goal ediGoal(Goal goal) {
        return goal;
    }
}
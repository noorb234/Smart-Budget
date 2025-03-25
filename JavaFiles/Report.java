package JavaFiles;
class Report extends Budget{
    public Integer reportID;
    private String timeFrame;
    private String startDate;
    private String endDate;
    private Float totalSpend;

    public Report() {
        reportID = null;
        timeFrame = null;
        startDate = null;
        endDate = null;
        totalSpend = null;
    }
    public Report(Integer reportID, String timeFrame, String startDate, String endDate, Float totalSpend) {
        this.reportID = reportID;
        this.timeFrame = timeFrame;
        this.startDate = startDate;
        this.endDate = endDate;
        this.totalSpend = totalSpend;
    }
    public Report(Integer budgetID, Float monthlyLimit, Category[] category, Integer userID, Integer reportID, String timeFrame, String startDate, String endDate, Float totalSpend) {
        super(budgetID, monthlyLimit, category, userID);
        this.reportID = reportID;
        this.timeFrame = timeFrame;
        this.startDate = startDate;
        this.endDate = endDate;
        this.totalSpend = totalSpend;
    }

    public Integer getReportID() {
        return reportID;
    }

    public void setReportID(Integer reportID) {
        this.reportID = reportID;
    }

    public String getTimeFrame() {
        return timeFrame;
    }

    public void setTimeFrame(String timeFrame) {
        this.timeFrame = timeFrame;
    }

    public String getStartDate() {
        return startDate;
    }

    public void setStartDate(String startDate) {
        this.startDate = startDate;
    }

    public String getEndDate() {
        return endDate;
    }

    public void setEndDate(String endDate) {
        this.endDate = endDate;
    }

    public Float getTotalSpend() {
        return totalSpend;
    }

    public void setTotalSpend(Float totalSpend) {
        this.totalSpend = totalSpend;
    }

    @Override
    public String toString() {
        return "Report{" +
                "reportID=" + reportID +
                ", timeFrame='" + timeFrame + '\'' +
                ", startDate='" + startDate + '\'' +
                ", endDate='" + endDate + '\'' +
                ", totalSpend=" + totalSpend +
                '}';
    }
}
@page
@model SearchModel
@{
    ViewData["Title"] = "搜尋";
}
<h2>搜尋</h2>
<form method="get">
    <input type="text" name="q" value="@Model.SearchQuery" />
    <button type="submit">搜尋</button>
</form>
@if (Model.SearchQuery != null)
{
    <p>您搜尋的關鍵字是: @Model.SearchQuery</p>
    <ul>
    @foreach (var product in Model.Results)
    {
        <li>@product.Name</li>
    }
    </ul>
}

// Razor Page C# 後端 (Search.cshtml.cs)
/*
public class SearchModel : PageModel
{
    private readonly IDbConnection _db;
    public string SearchQuery { get; set; }
    public List<Product> Results { get; set; } = new();
    public SearchModel(IDbConnection db) => _db = db;
    public void OnGet(string q)
    {
        SearchQuery = q;
        if (!string.IsNullOrEmpty(q))
        {
            Results = _db.Query<Product>("SELECT * FROM Products WHERE Name LIKE @name", new { name = "%" + q + "%" }).ToList();
        }
    }
}
*/

using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace App.Models
{
    public class Movie
    {
        public int ID { get; set; }
        
        [StringLength(60, MinimumLength = 3), Required]
        public string Title { get; set; } = string.Empty;

        [Display(Name = "Release Date"), DataType(DataType.Date)]
        public DateTime ReleaseDate { get; set; }
        
        [RegularExpression(@"^[A-ZÅÄÖ]+[a-zåäöA-ZÅÄÖ\s]*$"), Required, StringLength(30)]
        public string Genre { get; set; } = string.Empty;
        
        [Column(TypeName = "decimal(18, 2)"), Range(1, 100), DataType(DataType.Currency)]
        public decimal Price { get; set; }

        [RegularExpression(@"^[A-ZÅÄÖ]+[a-zåäöA-ZÅÄÖ0-9""'\s-]*$"), Required, StringLength(5)]
        public string Rating { get; set; } = string.Empty;
    }
}
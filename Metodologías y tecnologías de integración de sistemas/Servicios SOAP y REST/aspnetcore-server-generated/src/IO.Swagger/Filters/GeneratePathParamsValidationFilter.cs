using System.ComponentModel.DataAnnotations;
using System.Linq;
using Microsoft.AspNetCore.Mvc.Controllers;
using Microsoft.OpenApi.Models;
using Swashbuckle.AspNetCore.SwaggerGen;

namespace IO.Swagger.Filters
{
    /// <summary>
    /// Path Parameter Validation Rules Filter
    /// </summary>
    public class GeneratePathParamsValidationFilter : IOperationFilter
    {
        /// <summary>
        /// Constructor
        /// </summary>
        /// <param name="operation">Operation</param>
        /// <param name="context">OperationFilterContext</param>
        public void Apply(OpenApiOperation operation, OperationFilterContext context)
        {
            var pars = context.ApiDescription.ParameterDescriptions;

            foreach (var par in pars)
            {
                var swaggerParam = operation.Parameters.SingleOrDefault(p => p.Name == par.Name);

                if (par.ParameterDescriptor is ControllerParameterDescriptor descriptor && descriptor.ParameterInfo != null)
                {
                    var attributes = descriptor.ParameterInfo.CustomAttributes;

                    if (attributes.Any() && swaggerParam != null)
                    {
                        // Required - [Required]
                        var requiredAttr = attributes.FirstOrDefault(p => p.AttributeType == typeof(RequiredAttribute));
                        if (requiredAttr != null)
                        {
                            swaggerParam.Required = true;
                        }

                        // Regex Pattern [RegularExpression]
                        var regexAttr = attributes.FirstOrDefault(p => p.AttributeType == typeof(RegularExpressionAttribute));
                        if (regexAttr != null)
                        {
                            string regex = (string)regexAttr.ConstructorArguments[0].Value;
                            swaggerParam.Schema.Pattern = regex;
                        }

                        // String Length [StringLength]
                        int? minLenght = null, maxLength = null;
                        var stringLengthAttr = attributes.FirstOrDefault(p => p.AttributeType == typeof(StringLengthAttribute));
                        if (stringLengthAttr != null)
                        {
                            if (stringLengthAttr.NamedArguments.Count == 1)
                            {
                                minLenght = (int)stringLengthAttr.NamedArguments.Single(p => p.MemberName == "MinimumLength").TypedValue.Value;
                            }
                            maxLength = (int)stringLengthAttr.ConstructorArguments[0].Value;
                        }

                        var minLengthAttr = attributes.FirstOrDefault(p => p.AttributeType == typeof(MinLengthAttribute));
                        if (minLengthAttr != null)
                        {
                            minLenght = (int)minLengthAttr.ConstructorArguments[0].Value;
                        }

                        var maxLengthAttr = attributes.FirstOrDefault(p => p.AttributeType == typeof(MaxLengthAttribute));
                        if (maxLengthAttr != null)
                        {
                            maxLength = (int)maxLengthAttr.ConstructorArguments[0].Value;
                        }

                        swaggerParam.Schema.MinLength = minLenght;
                        swaggerParam.Schema.MaxLength = maxLength;

                        // Range [Range]
                        var rangeAttr = attributes.FirstOrDefault(p => p.AttributeType == typeof(RangeAttribute));
                        if (rangeAttr != null)
                        {
                            int rangeMin = (int)rangeAttr.ConstructorArguments[0].Value;
                            int rangeMax = (int)rangeAttr.ConstructorArguments[1].Value;

                            swaggerParam.Schema.Minimum = rangeMin;
                            swaggerParam.Schema.Maximum = rangeMax;
                        }
                    }
                }
            }
        }
    }
}

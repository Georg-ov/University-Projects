/*
 * API Edificio Inteligente
 *
 * API para la gestión de salas, niveles, dispositivos y notificaciones en un edificio inteligente.
 *
 * OpenAPI spec version: 1.0.0
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 */
using System;
using System.Linq;
using System.IO;
using System.Text;
using System.Collections;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel.DataAnnotations;
using System.Runtime.Serialization;
using Newtonsoft.Json;

namespace IO.Swagger.Models
{
    /// <summary>
    /// 
    /// </summary>
    [DataContract]
    public partial class NotificacionPresencia : IEquatable<NotificacionPresencia>
    { 
        /// <summary>
        /// Gets or Sets CodigoSala
        /// </summary>

        [DataMember(Name="codigoSala")]
        public int CodigoSala { get; set; }

        /// <summary>
        /// Returns the string presentation of the object
        /// </summary>
        /// <returns>String presentation of the object</returns>
        public override string ToString()
        {
            var sb = new StringBuilder();
            sb.Append("class NotificacionPresencia {\n");
            sb.Append("  CodigoSala: ").Append(CodigoSala).Append("\n");
            sb.Append("}\n");
            return sb.ToString();
        }

        /// <summary>
        /// Returns the JSON string presentation of the object
        /// </summary>
        /// <returns>JSON string presentation of the object</returns>
        public string ToJson()
        {
            return JsonConvert.SerializeObject(this, Formatting.Indented);
        }

        /// <summary>
        /// Returns true if objects are equal
        /// </summary>
        /// <param name="obj">Object to be compared</param>
        /// <returns>Boolean</returns>
        public override bool Equals(object obj)
        {
            if (ReferenceEquals(null, obj)) return false;
            if (ReferenceEquals(this, obj)) return true;
            return obj.GetType() == GetType() && Equals((NotificacionPresencia)obj);
        }

        /// <summary>
        /// Returns true if NotificacionPresencia instances are equal
        /// </summary>
        /// <param name="other">Instance of NotificacionPresencia to be compared</param>
        /// <returns>Boolean</returns>
        public bool Equals(NotificacionPresencia other)
        {
            if (ReferenceEquals(null, other)) return false;
            if (ReferenceEquals(this, other)) return true;

            return 
                (
                    CodigoSala == other.CodigoSala ||
                    CodigoSala != null &&
                    CodigoSala.Equals(other.CodigoSala)
                );
        }

        /// <summary>
        /// Gets the hash code
        /// </summary>
        /// <returns>Hash code</returns>
        public override int GetHashCode()
        {
            unchecked // Overflow is fine, just wrap
            {
                var hashCode = 41;
                // Suitable nullity checks etc, of course :)
                    if (CodigoSala != null)
                    hashCode = hashCode * 59 + CodigoSala.GetHashCode();
                return hashCode;
            }
        }

        #region Operators
        #pragma warning disable 1591

        public static bool operator ==(NotificacionPresencia left, NotificacionPresencia right)
        {
            return Equals(left, right);
        }

        public static bool operator !=(NotificacionPresencia left, NotificacionPresencia right)
        {
            return !Equals(left, right);
        }

        #pragma warning restore 1591
        #endregion Operators
    }
}
